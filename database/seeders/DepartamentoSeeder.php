<?php

namespace Database\Seeders;

use App\Models\Departamento;
use Illuminate\Database\Seeder;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear departamento padre (ULEAM - Institución)
        $uleam = Departamento::create([
            'nombre_departamento' => 'UNIVERSIDAD LAICA ELOY ALFARO DE MANABÍ',
            'nomenclatura' => 'ULEAM',
            'departamento_padre_id' => null,
            'departamento_categoria_id' => 1, // Institucional
            'activo' => true,
        ]);

        // 2. Crear departamento hijo (Facultad - con padre ULEAM)
        $facultad = Departamento::create([
            'nombre_departamento' => 'FACULTAD CIENCIAS VETERINARIAS Y TECNOLOGÍA',
            'nomenclatura' => 'FCVT',
            'departamento_padre_id' => $uleam->id, // Usar ID del departamento creado
            'departamento_categoria_id' => 2, // Facultad
            'activo' => true,
        ]);

        // 3. Crear departamento nieto (Carrera - con padre Facultad)
        Departamento::create([
            'nombre_departamento' => 'Ingeniería Agropecuaria',
            'nomenclatura' => 'CIA',
            'departamento_padre_id' => $facultad->id, // Usar ID de la facultad creada
            'departamento_categoria_id' => 3, // Carrera
            'activo' => true,
        ]);
    }
}
