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
        Schema::create('tipos_servicios_internos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_tipo_servicio');
            $table->string('descripcion')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::table('servicio_internos', function (Blueprint $table) {
            $table->foreignId('tipo_servicio_interno_id')->nullable()->constrained('tipos_servicios_internos')->onDelete('set null');

            $table->dropConstrainedForeignId('tipo_servicio_id');
            $table->dropColumn('tipo_servicio_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_servicios_internos');
    }
};
