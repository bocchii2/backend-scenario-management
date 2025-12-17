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
        //
        Schema::table("servicios", function (Blueprint $table) {
            $table->dropForeign(['categoria_servicio_id']);
            $table->dropColumn('categoria_servicio_id');
        });
        Schema::drop('categoria_servicios');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
