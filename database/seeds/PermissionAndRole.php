<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PermissionAndRole extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'admin',
            'manager',
            'superadmin',
            'owner'
        ];

        foreach ($data as $role) {
            Role::updateOrCreate(
                ['name' => $role],
                ['name' => $role]
            );
        }
    }
}
