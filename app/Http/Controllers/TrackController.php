<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
                "duration" => "required|string|max:255,date_format:H:i:s",
                "nft_id" => "required|string|max:100|unique:tracks,nft_id",
                "track" => "required|file|mimes:mp3,aac,wav,ogg,weba,flac,mp4a,m4a"
            ]);
        }
        catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        $user = auth()->user(); /**@var User $user*/
        $data = $request->only([
            "name",
            "description",
            "lyric",
            "daw",
            "duration",
            "nft_id"
        ]);
        $data["owner_id"] = $user->id;
        $data["genre_id"] = GenreController::get(Carbon::createFromFormat($request->input("duration"), "H:i:s"));

        $track = Track::create($data);
        try {
            $track->addMediaFromRequest("track")->toMediaCollection();
        } catch (FileDoesNotExist | FileIsTooBig $e) {

        }
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
