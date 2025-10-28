<?php

namespace Database\Seeders;

use App\Models\ServicioInterno;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiciosInternosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ServicioInterno::truncate();
        $servicios = [
            ['nombre_servicio' => 'Wifi', 'activo' => true],
            ['nombre_servicio' => 'Aire', 'activo' => true],
            ['nombre_servicio' => 'Seguridad', 'activo' => true],
            ['nombre_servicio' => 'Catering', 'activo' => true],
            ['nombre_servicio' => 'Transporte', 'activo' => true],
        ];
}

}