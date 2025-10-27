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
        Schema::create('departamento_categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Añadir FK a la tabla departamentos
        Schema::table('departamentos', function (Blueprint $table) {
            $table->foreignId('departamento_categoria_id')
                ->nullable()
                ->constrained('departamento_categorias')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departamentos', function (Blueprint $table) {
            $table->dropForeign(['departamento_categoria_id']);
            $table->dropColumn('departamento_categoria_id');
        });

        Schema::dropIfExists('departamento_categorias');
    }
};
