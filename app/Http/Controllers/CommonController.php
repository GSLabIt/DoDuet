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
}
