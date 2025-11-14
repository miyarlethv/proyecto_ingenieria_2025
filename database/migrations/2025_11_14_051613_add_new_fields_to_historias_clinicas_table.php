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
        Schema::table('historias_clinicas', function (Blueprint $table) {
            $table->string('nombre_responsable')->nullable()->after('descripcion');
            $table->string('telefono')->nullable()->after('nombre_responsable'); // Edad de la mascota
            $table->string('cargo')->nullable()->after('telefono');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historias_clinicas', function (Blueprint $table) {
            $table->dropColumn(['nombre_responsable', 'telefono', 'cargo']);
        });
    }
};
