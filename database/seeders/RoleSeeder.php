<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            "super-admin" => [],
            "banned" => [],
        ];

        foreach ($roles as $role => $permissions) {
            $rl = Role::findOrCreate($role); /**@var Role $rl*/
            foreach ($permissions as $permission) {
                $rl->givePermissionTo($permission);
            }
        }
    }
}
