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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('espacio_id')->constrained('espacios')->onDelete
('cascade');
            $table->foreignId('tipo_evento_id')->constrained('tipo_eventos');
            $table->string('nombre_evento');
            $table->string('descripcion')->nullable();
            $table->string('proposito')->nullable();
            $table->integer('numero_asistentes')->nullable();
            $table->date('fecha_evento_inicio');
            $table->date('fecha_evento_fin');
            $table->integer('duracion_horas');
            $table->boolean('montaje_requerido')->default(false);
            $table->date('fecha_montaje')->nullable();
            $table->integer('horas_montaje')->nullable();
            $table->boolean('evento_privado')->default(false);
            $table->boolean('grabacion_streaming')->default(false);
            $table->json('moviliarios_solicitados')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
