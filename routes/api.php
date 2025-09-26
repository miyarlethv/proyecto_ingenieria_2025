<?php

use App\Http\Controllers\FundacionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\MascotaController;

// Personas
Route::get('/', [PersonaController::class, 'index']);
Route::post('crear', [PersonaController::class, 'crear']);
Route::get('traerPersonaId', [PersonaController::class, 'traerPersonaId']);
Route::post('actualizar', [PersonaController::class, 'actualizar']);
Route::post('eliminarPorId', [PersonaController::class, 'eliminarPorId']);

// Fundaciones
Route::get('Fundacion', [FundacionController::class, 'index']);
Route::post('crearFundacion', [FundacionController::class, 'crear']);
Route::get('traerPersonaIdFundacion', [FundacionController::class, 'traerPersonaId']);
Route::post('actualizarFundacion', [FundacionController::class, 'actualizar']);
Route::post('eliminarPorIdFundacion', [FundacionController::class, 'eliminarPorId']);

// Mascotas
Route::post('CrearMascotas', [MascotaController::class, 'crear']);
Route::get('/mascotas', [MascotaController::class, 'index']);
Route::put('ActualizarMascotas', [MascotaController::class, 'actualizar']);
Route::delete('EliminarMascotas', [MascotaController::class, 'eliminar']);
