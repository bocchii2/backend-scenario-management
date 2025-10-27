<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Cargo::create(['nombre_cargo' => 'Gerente', 'descripcion' => 'Responsable de la gestión general']);
        Cargo::create(['nombre_cargo' => 'Desarrollador', 'descripcion' => 'Encargado del desarrollo de software']);
        Cargo::create(['nombre_cargo' => 'Diseñador', 'descripcion' => 'Responsable del diseño de interfaces']);
        Cargo::create(['nombre_cargo' => 'Analista', 'descripcion' => 'Encargado del análisis de requisitos']);
        Cargo::create(['nombre_cargo' => 'Administrador de Sistemas', 'descripcion' => 'Responsable de la administración de sistemas']);
    }
}
