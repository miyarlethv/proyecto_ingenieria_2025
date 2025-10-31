<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::table('funcionarios', function (Blueprint $table) {
        // Primero eliminar la relación (si existe)
        $table->dropForeign(['rol_id']); // nombre de la clave foránea

        // Luego eliminar la columna
        $table->dropColumn('rol_id');
    });
}


    public function down(): void
    {
        Schema::table('funcionarios', function (Blueprint $table) {
            $table->unsignedBigInteger('rol_id')->nullable(); // Para revertir la migración
        });
    }
};

