<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Response;
use Inertia\ResponseFactory;

class DashboardController extends Controller
{
    /**
     * Dashboard main page
     * @return Response|ResponseFactory
     */
    public function index(): Response|ResponseFactory
    {
        $user = auth()->user(); /**@var User $user*/
        return inertia("Dashboard", [
            "tracks" => $user->tracks
        ]);
    }

    public function walletRequired(): Response|ResponseFactory
    {
        return inertia("User/" . directoryFromClass(__CLASS__, false) . "/" . Str::camel(__FUNCTION__), []);
    }
}
