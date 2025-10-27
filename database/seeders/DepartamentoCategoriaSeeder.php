<?php

namespace Database\Seeders;

use App\Models\DepartamentoCategoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartamentoCategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DepartamentoCategoria::create([
            "nombre" => "Institucion",
            "descripcion" => "Departamento encargado de la gestión institucional",
            "activo" => true,
        ]);
        DepartamentoCategoria::create([
            "nombre" => "Facultad",
            "descripcion" => "Departamento encargado de la gestión académica",
            "activo" => true,
        ]);
        DepartamentoCategoria::create([
            "nombre" => "Carrera",
            "descripcion" => "Departamento encargado de la gestión de las carreras",
            "activo" => true,
        ]);
    }
}
