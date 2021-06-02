<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
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

    public function retrieveToken(): JsonResponse
    {
        if(!session()->has("bearer")) {
            $user = auth()->user(); /**@var User $user*/
            $user->tokens()->delete();

            $token = $user->createToken(now()->timestamp . "-" . $user->email . "-" . Str::random())->plainTextToken;
            session()->put("bearer", $token);

            return response()->json([
                "bearer" => $token
            ])->withCookie(Cookie::make("voting_id", encrypt($token)));
        }
        return response()->json([
            "bearer" => session("bearer")
        ]);
    }
}
