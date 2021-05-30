<?php

namespace App\Http\Controllers;

use App\Http\Controllers\NFTHelper\ElectionsController;
use App\Http\Controllers\NFTHelper\TimeController;
use App\Http\Controllers\NFTHelper\VoteController;
use App\Http\Controllers\NFTHelper\Web3Address;
use App\Http\Controllers\NFTSession\NFTSession;
use App\Http\Controllers\NodeBackEnd\NodeBackEnd;
use App\Models\Election;
use App\Models\ListeningRequest;
use App\Models\Track;
use App\Models\User;
use App\Models\VoteRequest;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class PublicController extends Controller
{
    /**
     * Return the reference to the nft
     * @param string $nft_id
     * @return JsonResponse
     */
    public function nftReference(string $nft_id): JsonResponse
    {
        $track = Track::where("nft_id", $nft_id)->first();
        if (!is_null($track)) {
            return response()->json([
                "name" => $track->name,
                "owner" => $track->owner->name,
                "description" => $track->description,
                "lyric" => $track->lyric,
                "daw" => $track->daw,
                "genre" => $track->genre->name,
                "duration" => $track->duration,
                "artist" => $track->creator->name,
            ]);
        }
        abort(404);
    }

    /**
     * Return the temporary url and some additional data for the playback of the track
     * @param string $nft_id
     * @param string|null $address
     * @return JsonResponse
     */
    public function requestNftTrackAccess(string $nft_id, ?string $address): JsonResponse
    {
        // check if address is provided in the given request and is not valid if so, return the error
        if (!is_null($address) && !Web3Address::validateAddress($address, $err)) {
            return simpleJSONError($err);
        }

        if (auth()->guest()) {
            return simpleJSONError("Listening allowed only to registered users", 401);
        }

        $track = Track::where("nft_id", $nft_id)->first();
        if (!is_null($track) && NFTSession::canRequestNftTrackAccess()) {
            $user = auth()->user(); /**@var User $user */
            $election = ElectionsController::getCurrentElection();

            // retrieve the current listening request if one exists
            $listening_request = ListeningRequest::where("election_id", $election->id)
                ->where("voter_id", $user->id)
                ->where("track_id", $track->id)
                ->first();

            // create a new listening request if address is provided and it does not exists
            if (is_null($listening_request) && !is_null($address)) {
                ListeningRequest::create([
                    "election_id" => $election->id,
                    "voter_id" => $user->id,
                    "track_id" => $track->id
                ]);

                VirtualBalanceController::addListeningPrize($address);
            }

            $track_time = TimeController::getTrackDuration($track);
            $duration = TimeController::getTrackListeningDuration($track_time);
            $url_elapse_time = TimeController::getUrlElapseTime($duration);

            $tmp_url = $track->getFirstMedia()->getTemporaryUrl($url_elapse_time);

            // save in session the url elapse time, the nft_id and the temporary url
            NFTSession::update()->trackAccessedRequested($url_elapse_time, $nft_id, $tmp_url);

            return response()->json([
                "track_duration" => $track->duration,
                "url" => $tmp_url,
                "url_lifetime" => $duration->format("H:i:s")
            ]);
        } elseif (!is_null($track) &&
            // in case a request cannot be made again check if it is still valid and use that data
            NFTSession::states()->hasTempUrl() &&
            NFTSession::times()->isNotPlayingTimeElapsed()
        ) {
            $url_lifetime = TimeController::getTrackListeningDuration(
                Carbon::createFromFormat("H:i:s", $track->duration)
            );

            return response()->json([
                "track_duration" => $track->duration,
                "url" => NFTSession::states()->getTempUrl(),
                "url_lifetime" => $url_lifetime->format("H:i:s")
            ]);
        }

        return simpleJSONError("Track not found or already requested", 404);
    }

    /**
     * @param string $nft_id
     * @param string $address
     * @return JsonResponse
     */
    public function requestNftTrackVote(string $nft_id, string $address): JsonResponse
    {
        // check if address is provided in the given request
        if (!Web3Address::validateAddress($address, $err)) {
            return simpleJSONError($err);
        }

        if (auth()->guest()) {
            return simpleJSONError("Vote allowed only to registered users", 401);
        }

        // check if track exists
        $user = auth()->user(); /**@var User $user */
        $track = Track::where("nft_id", $nft_id)->first();
        $time_difference = Carbon::createFromTimestamp(NFTSession::times()->getPlaying())->diffInSeconds(absolute: false);

        if (!is_null($track)) {
            // Check that the user is not trying to vote his song
            if (VoteController::currentUserIsOwner($track)) {
                return simpleJSONError("Cannot vote your own tracks", 401);
            }

            // check that the elapsed time is at least equal to the duration of the song before actually requesting the
            // possibility to vote
            $duration = TimeController::getTrackDuration($track);
            $duration_secs = $duration->hour * 3600 + $duration->minute * 60 + $duration->second;

            $election = ElectionsController::getCurrentElection();

            // retrieve an eventually existing vote request
            $vote_request = VoteRequest::where("election_id", $election->id)
                ->where("voter_id", $user->id)
                ->where("track_id", $track->id)
                ->first();

            if ( // check that the user can vote the track and that the track was in listening state for at least the track duration
                (NFTSession::canRequestNftVotingAccess($nft_id) && $time_difference >= -$duration_secs) ||
                (!is_null($vote_request) && $vote_request->confirmed)
            ) {
                // if a vote request does not exist create one and update the states
                if (is_null($vote_request)) {
                    VoteRequest::create([
                        "election_id" => $election->id,
                        "voter_id" => $user->id,
                        "track_id" => $track->id
                    ]);
                    NFTSession::update()->trackVoteRequested();

                    VirtualBalanceController::addListeningPrize($address);

                    // send the request to the node backend to trigger the user addition to the vote array
                    $response = NodeBackEnd::endpoints()->sendVoteRequest($address, $nft_id);

                    if ($response["status"] !== 200) {
                        logger()->error($response["body"]);
                        return response()->json([
                            "submitted" => false
                        ]);
                    }

                    // an event is fired once the vote is confirmed, a websocket connection will wait for it
                    // and forward it to the client, now the client will have to wait for the websocket confirmation

                    // TODO: Place the client in a waiting state for the confirmation of the possibility to vote using websockets

                    // this is an answer of LOCAL request completion, the blockchain is not updated yet, a notification will
                    // be send once it occurs
                    return response()->json([
                        "submitted" => true
                    ]);
                }

                return response()->json([
                    "submitted" => false
                ]);
            }

            return simpleJSONError("Voting requirements not respected, listen to the song and try again", 401);
        }
        return simpleJSONError("Track not found", 404);
    }

    public function recordNftTrackVote(Request $request, string $nft_id)
    {
        $track = Track::where("nft_id", $nft_id)->first();
        if (!is_null($track)) {


            // TODO
        }
        abort(404);
    }
}
