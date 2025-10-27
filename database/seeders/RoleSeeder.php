<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create(['nombre' => 'Administrador', 'descripcion' => 'Usuario con todos los permisos del sistema', 'slug' => 'administrador']);
        Role::create(['nombre' => 'Usuario', 'descripcion' => 'Usuario con permisos limitados', 'slug' => 'usuario']);
    }
}
