<?php

namespace Database\Seeders;

use App\Http\Controllers\UserSegmentsController;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => "Do Group LLC",
            'email' => "emanuele.balsamo@do-inc.co",
            'password' => Hash::make(env("SUPER_ADMIN_PSW")),
        ]);
        secureUser($user)->set("password", env("SUPER_ADMIN_PSW"));

        $user->wallet()->create();
        $user->deposit(5000);

        UserSegmentsController::assignToSegment($user);
        $user->assignRole("super-admin");
    }
}
