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
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->enum('tipo_documento', ['CC', 'TI', 'CE'])->nullable();
            $table->string('nit', 50)->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 150)->unique()->nullable();
            $table->unsignedBigInteger('rol_id');
            $table->string('password');
    
            $table->timestamps();

            // Clave foránea (asumiendo que existe la tabla roles)
            $table->foreign('rol_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
