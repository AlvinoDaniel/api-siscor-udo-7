<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = Role::create([
            'name' => 'administrador',
            'guard_name' => 'api'
        ]);

        $estandar = Role::create([
            'name' => 'estandar',
            'guard_name' => 'api'
        ]);

    }
}