<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class LandingPageController extends Controller
{
    /**
     * Main public page of the website
     * @return Response|ResponseFactory
     */
    public function index(): Response|ResponseFactory
    {
        // Admin user is not counted as registered
        $registered_users = User::count() -1;
        return inertia("Welcome", [
            "referral_prize" => (int)env("REFERRAL_PRIZE"),
            "registered_users" => $registered_users < 1000 ? 1000 - $registered_users : 0
        ]);
    }
}
