<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * Display the specified resource.
     *
     * @param  \App\Models\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function show(Track $track)
    {
        //
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function destroy(Track $track)
    {
        //
    }
}
