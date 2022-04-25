<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CommonController extends Controller
{
    public function referralKeeper(Request $request) {
        $request->validate([
            "ref" => "required|string|size:40|regex:/[0-9a-f]{40}/"
        ]);

        session()->put("referral_code", $request->input("ref"));
    }

    public function dashboard(Request $request) {
        return inertia('Dashboard');
    }

    public function challengeIndex(Request $request) {
        return inertia('Challenge/Index');
    }

    public function tracksIndex(Request $request) {
        return inertia('Tracks/Index');
    }

    public function trackUpload(Request $request) {
        return inertia('Tracks/Upload');
    }

    public function coversIndex(Request $request) {
        return inertia('Cover/Index');
    }

    public function coverUpload(Request $request) {
        return inertia('Cover/Upload');
    }

    public function albumsIndex(Request $request) {
        return inertia('Album/Index');
    }

    public function albumCreate(Request $request) {
        return inertia('Album/Create');
    }

    public function lyricsIndex(Request $request) {
        return inertia('Lyric/Index');
    }

    public function lyricCreate(Request $request) {
        return inertia('Lyric/Create');
    }

}
