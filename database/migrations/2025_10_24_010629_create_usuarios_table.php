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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombres_completos')->nullable();
            $table->string('correo_electronico')->nullable()->unique();
            $table->string('tipo_identificacion')->nullable();
            $table->string('identificacion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('password')->nullable();
            $table->boolean('activo')->default(true)->nullable();
            $table->timestamps();

            // Relaciones con departamentos
            $table->foreignId('cargo_id')->nullable()->constrained('cargos')->onDelete('cascade');
            $table->foreignId('departamento_id')->nullable()->constrained('departamentos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
