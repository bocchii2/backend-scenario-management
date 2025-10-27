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
        $tables = ['departamentos', 'roles', 'cargos', 'espacios', 'usuarios'];

        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $t) {
                // auditoría: usuario que creó / actualizó
                $t->foreignId('created_by')->nullable()->constrained('usuarios')->nullOnDelete();
                $t->foreignId('updated_by')->nullable()->constrained('usuarios')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['departamentos', 'roles', 'cargos', 'espacios', 'usuarios'];

        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            // drop columns / foreigns only if exist
            if (Schema::hasColumn($table, 'updated_by') || Schema::hasColumn($table, 'created_by')) {
                Schema::table($table, function (Blueprint $t) use ($table) {
                    if (Schema::hasColumn($table, 'updated_by')) {
                        // drop foreign then column
                        $t->dropForeign(['updated_by']);
                        $t->dropColumn('updated_by');
                    }

                    if (Schema::hasColumn($table, 'created_by')) {
                        $t->dropForeign(['created_by']);
                        $t->dropColumn('created_by');
                    }
                });
            }
        }
    }
};
