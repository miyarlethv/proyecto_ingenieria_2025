<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('historias_clinicas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mascota_id'); // Relación con mascota
            $table->date('fecha');
            $table->text('descripcion');
            $table->string('tipo')->nullable();
            $table->timestamps();

            $table->foreign('mascota_id')
                  ->references('id')->on('mascotas')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historias_clinicas');
    }
};
