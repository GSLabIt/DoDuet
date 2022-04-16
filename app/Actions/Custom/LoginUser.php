<?php

namespace App\Actions\Custom;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginUser
{
    public function __invoke(Request $request): ?User
    {
        return $this->login($request);
    }

    /**
     * Custom login methodology
     *
     * @param Request $request
     * @return User|null
     */
    public function login(Request $request): ?User
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required|string"
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user &&
            Hash::check($request->password, $user->password)) {
            secureUser($user)->set("password", $request->password);
            return $user;
        }

        return null;
    }
}
