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
        Schema::create('solicitudes_adopcion', function (Blueprint $table) {
            $table->id();
            
            // Relaciones con otras tablas
            $table->foreignId('mascota_id')->constrained('mascotas')->onDelete('cascade');
            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');
            
            // Campos del formulario
            $table->integer('edad');
            $table->string('ciudad_residencia');
            $table->string('ocupacion');
            $table->string('estrato_social');
            $table->enum('tiene_hijos', ['Sí', 'No']);
            $table->integer('numero_personas_hogar');
            $table->enum('acepta_seguimiento', ['Sí', 'No']);
            
            // Estado de la solicitud
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_adopcion');
    }
};
