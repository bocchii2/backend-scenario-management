<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('espacios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_espacio');
            $table->integer('capacidad')->nullable();
            $table->string('descripcion')->nullable();
            $table->integer('metro_cuadrado')->nullable();
            $table->integer('pies_cuadrados')->nullable();
            $table->integer('altura')->nullable();
            $table->json('otros_atributos')->nullable();
            $table->json('atributos_capacidad')->nullable();
            
            $table->foreignId('categoria_espacio_id')->constrained('categoria_espacios')->onDelete('cascade');

            $table->foreignId('departamento_id')->constrained('departamentos')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('espacios');
    }
};
