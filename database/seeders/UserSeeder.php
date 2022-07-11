<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder 
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::Create([
            'name'      => 'Administrador',
            'apellido'  => 'Admin',
            'username'  => 'admin',
            'password'  => '123456',
            'email'     => 'administrador@test.com',
        ]);
        $standar = User::Create([
            'name'      => 'Estandar',
            'apellido'  => 'User',
            'username'  => 'estandar',
            'password'  => '123456',
            'email'     => 'estandar@test.com',
        ]);

        $admin->assignRole('administrador');
        $standar->assignRole('jefe');
        $standar->assignRole('secretario');
    }
}
