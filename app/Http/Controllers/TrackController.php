<?php

namespace App\Http\Controllers;

use App\Http\Controllers\NFTHelper\ElectionsController;
use App\Models\Track;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Response;
use Inertia\ResponseFactory;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // TODO: implement featured tracks and show up to 3 featured tracks randomly extracted
        return response()->json([
            "featured_tracks" => [],
            "latest_tracks" => Track::orderBy("created_at")->limit(9)->get(["nft_id"])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response|ResponseFactory
     */
    public function create(): Response|ResponseFactory
    {
        return inertia("User/" . directoryFromClass(__CLASS__, false) . "/" . __FUNCTION__, []);
    }

    /**
     * Perform only the validation phase of the store method, in order to validate the
     * data before nft creation on the front end
     * @param Request $request
     * @return RedirectResponse
     */
    public function soloValidation(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                "name" => "required|string|max:65535",
                "description" => "nullable|string",
                "lyric" => "nullable|string",
                "daw" => "nullable|string|max:65535",
                "duration" => "required|string|max:255|regex:/\d{2}\:\d{2}\:\d{2}/",
                "track" => "required|file|mimes:mp3,aac,wav,ogg,weba,flac,mp4a,m4a",
            ]);
        }
        catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                "name" => "required|string|max:65535",
                "description" => "nullable|string",
                "lyric" => "nullable|string",
                "daw" => "nullable|string|max:65535",
                "duration" => "required|string|max:255|regex:/\d{2}\:\d{2}\:\d{2}/",
                "nft_id" => "required|numeric|max:100|unique:tracks,nft_id",
                "track" => "required|file|mimes:mp3,aac,wav,ogg,weba,flac,mp4a,m4a",
            ]);
        }
        catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        // Send the request to initialize the nft right after the creation of the record
        $user = auth()->user(); /**@var User $user*/
        $data = $request->only([
            "name",
            "description",
            "lyric",
            "daw",
            "duration",
            "nft_id"
        ]);
        $data["owner_id"] = $data["creator_id"] = $user->id;
        $data["genre_id"] = GenreController::get(Carbon::createFromFormat("H:i:s", $request->input("duration")));

        $track = Track::create($data);
        try {
            $track->addMediaFromRequest("track")->toMediaCollection();
        } catch (FileDoesNotExist | FileIsTooBig $e) {
            $track->delete();
            return back()->withErrors(["track" => $e->getMessage()]);
        }

        //return redirect()->route("dashboard");
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function edit(Track $track)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Track $track)
    {
        //
    }

    /**
     * Get the total number of listening request a track has received
     * @param Track $track
     * @return JsonResponse
     */
    public function getListeningNumber(Track $track): JsonResponse
    {
        return response()->json(["listened" => $track->listening->count()]);
    }

    /**
     * Get the total number of votes a track has received
     * @param Track $track
     * @return JsonResponse
     */
    public function getVotesNumber(Track $track): JsonResponse
    {
        return response()->json(["voted" => $track->votes->count()]);
    }

    /**
     * Get an average of the total votes a track has received
     * @param Track $track
     * @return JsonResponse
     */
    public function getAverageVote(Track $track): JsonResponse
    {
        $votes_number = $this->getVotesNumber($track)->getData(true)["voted"];
        $total = 0;
        foreach ($track->votes as $vote) {
            $total += $vote->half_stars;
        }
        return response()->json(["average" => $total / ($votes_number > 0 ? $votes_number : 1)]);
    }

    public function isParticipatingInElection(Track $track): JsonResponse
    {
        return response()->json(["participating" => ElectionsController::trackParticipateInCurrentElection($track)]);
    }

    /**
     * Return the vote given by the current user to the track in the current election
     * @param Track $track
     * @return JsonResponse
     */
    public function getRegisteredVote(string $nft_id): JsonResponse
    {
        $track = Track::where("nft_id", $nft_id)->first();

        if(is_null($track)) {
            return simpleJSONError("Track not found", 404);
        }

        // check the user is logged in
        if (auth()->guest()) {
            return simpleJSONError("Vote allowed only to registered users", 401);
        }

        $user = auth()->user(); /**@var User $user*/
        $election = ElectionsController::getCurrentElection();

        $stars = $track->votes()
            ->where("voter_id", $user->id)
            ->where("election_id", $election->id)
            ->select(["half_stars"])->first();

        if(!is_null($stars)) {
            $stars /= 2;
        }

        return response()->json([
            "stars" => $stars
        ]);
    }

    /**
     * Let the track participate in the current election
     * @param string $nft_id
     * @return RedirectResponse|JsonResponse
     */
    public function participateToElection(string $nft_id): RedirectResponse|JsonResponse
    {
        try {
            Validator::validate([
                "nft_id" => $nft_id
            ], [
                "nft_id" => "required|numeric|max:100|exists:tracks,nft_id",
            ]);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 400);
        }

        $track = Track::where("nft_id", $nft_id)->first();
        if(!ElectionsController::trackParticipateInCurrentElection($track)) {
            $election = ElectionsController::getCurrentElection();
            $election->tracks()->save($track);

            return response()->json(["submitted" => true]);
        }

        return simpleJSONError("Track already participating to election", 401);
    }
}
