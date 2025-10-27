<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssignRoleAmdinToAdminUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = Usuario::where('correo_electronico', 'admin@example.com')->first();
        $user->roles()->attach(1); // Asumiendo que el rol con ID 1 es 'Administrador'
    }
}
