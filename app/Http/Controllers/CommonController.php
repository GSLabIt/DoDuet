<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class CommonController extends Controller
{
    public function referralKeeper(Request $request) {
        $request->validate([
            "ref" => "required|string|size:40|regex:/[0-9a-f]{40}/"
        ]);

        session()->put("referral_code", $request->input("ref"));
    }

    public function dashboard(): Response|ResponseFactory {
        return inertia('Dashboard');
    }

    public function challengeIndex(): Response|ResponseFactory {
        return inertia('Challenge/Index');
    }
}
