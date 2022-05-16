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
            "super-admin" => [
                "report_reasons.create.*",
                "report_reasons.update.*",
                "report_reasons.delete.*",
                "ban.user",
                "unban.user"
            ],
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
