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
        Schema::create('fundaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('nit', 10)->unique(); // NIT o cédula única
            $table->string('email', 100)->unique();
            $table->string('telefono', 10)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('password'); // Se recomienda guardar con hash
            $table->string('slogan', 150)->nullable();
            $table->string('logo', 300)->nullable(); // Ruta del archivo cargado
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fundaciones');
    }


};
