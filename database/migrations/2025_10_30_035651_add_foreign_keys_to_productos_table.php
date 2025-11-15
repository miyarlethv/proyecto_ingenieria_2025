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
        Schema::table('productos', function (Blueprint $table) {
            // Agregar columnas de foreign keys
            $table->foreignId('categoria_id')->nullable()->after('id')->constrained('categorias')->onDelete('set null');
            $table->foreignId('nombre_id')->nullable()->after('categoria_id')->constrained('nombres')->onDelete('set null');
            
            // Hacer que los campos antiguos sean opcionales
            $table->string('nombre')->nullable()->change();
            $table->string('categoria')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropForeign(['nombre_id']);
            $table->dropColumn(['categoria_id', 'nombre_id']);
        });
    }
};
