<?php

namespace App\Http\Controllers;

use App\Http\Controllers\NFTSession\NFTSession;
use App\Http\Controllers\NodeBackEnd\NodeBackEnd;
use App\Models\Election;
use App\Models\Track;
use App\Models\User;
use App\Models\VoteRequest;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
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
        if(!is_null($track)) {
            return response()->json([
                "name" => $track->name,
                "owner" => $track->owner->name,
                "description" => $track->description,
                "lyric" => $track->lyric,
                "daw" => $track->daw,
                "genre" => $track->genre->name,
                "duration" => $track->duration,
                "creator" => $track->creator->name,
            ]);
        }
        abort(404);
    }

    /**
     * Return the temporary url and some additional data for the playback of the track
     * @param string $nft_id
     * @return JsonResponse
     */
    public function requestNftTrackAccess(string $nft_id): JsonResponse
    {
        // TODO: Remove this as soon as possible, it is made for testing only!!!!!
        //NFTSession::update()->globalBypass();

        $track = Track::where("nft_id", $nft_id)->first();
        if(!is_null($track) && NFTSession::canRequestNftTrackAccess()) {
            $track_time = Carbon::createFromFormat("H:i:s", $track->duration);
            $duration = $track_time
                ->addMinutes(env("TRACK_LISTENING_TIME_MINUTES"))
                ->addSeconds(env("TRACK_LISTENING_TIME_SECONDS"));
            $url_elapse_time = now()->addHours($duration->hour)
                ->addMinutes($duration->minute)
                ->addSeconds($duration->second);

            NFTSession::update()->trackAccessedRequested($url_elapse_time, $nft_id);

            return response()->json([
                "track_duration" => $track->duration,
                "url" => $track->getFirstMedia()
                    ->getTemporaryUrl($url_elapse_time),
                "url_lifetime" => $duration->format("H:i:s")
            ]);
        }
        abort(404, "Track already requested");
    }

    /**
     * @param string $nft_id
     * @return JsonResponse
     */
    public function requestNftTrackVote(string $nft_id, string $address): JsonResponse
    {
        // check if address is provided in the given request
        try {
            Validator::validate(
                [
                    "address" => $address
                ],
                [
                    "address" => "required|string|max:42|regex:/0x[A-Fa-f0-9]{40}/"
                ]);
        }
        catch (ValidationException $exception) {
            return response()->json([
                "error" => $exception->getMessage()
            ]);
        }

        // check if track exists
        $user = auth()->user(); /**@var User $user*/
        $track = Track::where("nft_id", $nft_id)->first();
        $time_difference = Carbon::createFromTimestamp(NFTSession::times()->getPlaying())->diffInSeconds(absolute: false);

        if(!is_null($track)) {
            // Check that the user is not trying to vote his song
            if($track->owner_id === $user->id) {
                return response()->json([
                    "error" => "Cannot vote your own tracks"
                ], 401);
            }

            // check that the elapsed time is at least equal to the duration of the song before actually requesting the
            // possibility to vote
            $duration = Carbon::createFromFormat("H:i:s", $track->duration);
            $duration_secs = $duration->hour * 3600 + $duration->minute * 60 + $duration->second;
            if(NFTSession::canRequestNftVotingAccess($nft_id) && $time_difference >= -$duration_secs) {

                $week_start = now()->startOfWeek(Carbon::MONDAY);
                $week_end = now()->endOfWeek(Carbon::SUNDAY);
                $user = auth()->user();
                /**@var User $user */
                $election = Election::whereBetween("created_at", [$week_start, $week_end])->first();

                // retrieve an eventually existing vote request
                $vote_request = VoteRequest::where("election_id", $election->id)
                    ->where("voter_id", $user->id)
                    ->where("track_id", $track->id)
                    ->first();

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

                    if($response["status"] !== 200) {
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

            return response()->json([
                "error" => "Voting requirements not respected, listen to the song and try again"
            ], 401);
        }
        return response()->json([
            "error" => "Track not found"
        ], 404);
    }

    public function recordNftTrackVote(Request $request, string $nft_id) {
        $track = Track::where("nft_id", $nft_id)->first();
        if(!is_null($track)) {


            // TODO
        }
        abort(404);
    }
}
