<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $entidades = [
            'usuarios',
            'roles',
            'cargos',
            'departamentos',
            'categoria_espacios',
            'espacios',
            'reservas',
        ];

        $acciones = ['crear', 'ver', 'editar', 'eliminar'];

        foreach ($entidades as $id_entidad) {
            foreach ($acciones as $accion) {
                \App\Models\Permission::create([
                    'entidad' => "{$id_entidad}",
                    'accion' => "{$accion}",
                    'nombre_completo' => "{$accion}_{$id_entidad}",
                ]);
            }
        }
    }
}
