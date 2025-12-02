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
        Schema::create('categoria_servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_categoria');
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('servicios', function (Blueprint $table) {
            $table->foreignId('categoria_servicio_id')->nullable()->constrained('categoria_servicios')->onDelete('set null');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropForeign(['categoria_servicio_id']);
            $table->dropColumn('categoria_servicio_id');
        });

        Schema::dropIfExists('categoria_servicios');
    }
};
