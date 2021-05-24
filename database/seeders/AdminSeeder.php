<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::firstOrCreate(
            [
                "name" => "admin",
                "email" => "admin@doduet.studio",
            ],
            [
                "name" => "admin",
                "email" => "admin@doduet.studio",
                "password" => Hash::make("jb38b*IBJEtE6N4owe5#4q%mOs0QoRH!^gCZ1MgFTc^trA3tXG")
            ]
        );
        $admin->assignRole("super-admin");
    }
}
