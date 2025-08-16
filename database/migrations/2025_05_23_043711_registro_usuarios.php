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
        Schema::create('personas', function (Blueprint $table) {
            $table->id(); // Campo 'id' como primary key autoincremental
            $table->string('nit', 10);
            $table->string('nombre', 50);
            $table->string('email', 50);
            $table->string('telefono', 10);
            $table->string('direccion', 100);
            $table->string('password', 250);
            $table->timestamps(); // Agrega campos created_at y updated_at
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }

};
