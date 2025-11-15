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
        Schema::create('mobiliarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_mobiliario');
            $table->string('descripcion')->nullable();
            $table->json('detalles_tecnicos')->nullable();
            $table->boolean('activo')->default(true);
            $table->foreignId('categoria_mobiliario_id')->constrained('categoria_mobiliarios')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobiliarios');
    }
};
