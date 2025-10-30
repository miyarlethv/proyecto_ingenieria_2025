<?php

use App\Http\Controllers\FundacionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\InicioSesionController;
use App\Http\Controllers\HistoriaClinicaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\FuncionarioController;

// Personas
Route::get('/', [PersonaController::class, 'index']);
Route::post('crear', [PersonaController::class, 'crear']);
Route::get('traerPersonaId', [PersonaController::class, 'traerPersonaId']);
Route::post('actualizar', [PersonaController::class, 'actualizar']);
Route::post('eliminarPorId', [PersonaController::class, 'eliminarPorId']);

// Login
Route::post('login', [InicioSesionController::class, 'login']);

// Fundaciones
Route::get('Fundacion', [FundacionController::class, 'index']);
Route::post('crearFundacion', [FundacionController::class, 'crear']);
Route::get('traerPersonaIdFundacion', [FundacionController::class, 'traerPersonaId']);
Route::post('actualizarFundacion', [FundacionController::class, 'actualizar']);
Route::post('eliminarPorIdFundacion', [FundacionController::class, 'eliminarPorId']);

// Mascotas
Route::post('CrearMascotas', [MascotaController::class, 'crear']);
Route::get('mascotas', [MascotaController::class, 'index']);
Route::put('ActualizarMascotas', [MascotaController::class, 'actualizar']);
Route::put('EliminarMascotas', [MascotaController::class, 'eliminar']);


// Historias Clinicas
Route::post('CrearHistoriaClinica', [HistoriaClinicaController::class, 'crear']);
Route::get('ListarHistoriasClinicas', [HistoriaClinicaController::class, 'index']);
Route::put('ActualizarHistoriaClinica', [HistoriaClinicaController::class, 'actualizar']);
Route::put('EliminarHistoriaClinica', [HistoriaClinicaController::class, 'eliminar']);

// Roles
Route::post(uri: 'CrearRol', action: [RolController::class, 'store']);
Route::get(uri: 'ListarRoles', action: [RolController::class, 'index']);
Route::put(uri: 'ActualizarRol', action: [RolController::class, 'actualizar']);
Route::put(uri: 'EliminarRol', action: [RolController::class, 'eliminar']);

// Permisos
Route::post(uri: 'CrearPermiso', action: [PermisoController::class, 'store']);
Route::get(uri: 'ListarPermisos', action: [PermisoController::class, 'index']);
Route::put(uri: 'ActualizarPermiso', action: [PermisoController::class, 'actualizar']);
Route::put(uri: 'EliminarPermiso', action: [PermisoController::class, 'eliminar']);

// Personas
Route::get('ListarFuncionarios', [FuncionarioController::class, 'index']);
Route::post('CrearFuncionario', [FuncionarioController::class, 'store']);
Route::put('ActualizarFuncionario', [FuncionarioController::class, 'actualizar']);
Route::put('EliminarFuncionario', [FuncionarioController::class, 'eliminar']);

