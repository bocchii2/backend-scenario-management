<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario admin
        Usuario::create([
            'nombres_completos' => 'Admin User',
            'correo_electronico' => 'admin@example.com',
            'cargo_id' => 1, // Asumiendo que el cargo con ID 1 es 'Administrador de Sistemas'
            'departamento_id' => 2, // Asumiendo que el departamento con ID 1 es 'Sistemas'
            'password' => bcrypt('password'), // Establecer una contraseña segura
            'telefono' => '1234567890',
            'tipo_identificacion' => 'Cédula',
            'identificacion' => '0102030405',

        ]);
    }

}
