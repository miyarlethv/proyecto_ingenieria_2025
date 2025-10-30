<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     */
    public function up(): void
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo_documento');
            $table->string('nit')->unique();
            $table->string('telefono')->nullable();
            $table->string('email')->unique();
            $table->foreignId('rol_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
