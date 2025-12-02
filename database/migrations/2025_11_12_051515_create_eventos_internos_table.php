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
        Schema::create('eventos_internos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('horario_id')->constrained('horarios')->onDelete('cascade');
            $table->foreignId('tipo_evento_interno_id')->constrained('tipos_eventos_internos')->onDelete('cascade');
            $table->string('nombre_evento');
            $table->boolean('evento_privado')->default(false);
            $table->string('descripcion')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->foreignId('departamento_organizador_id')->constrained('departamentos')->onDelete('cascade'); // indica el departamento organizador
            $table->string('contacto_principal')->nullable();
            $table->string('contacto_responsable')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos_internos');
    }
};
