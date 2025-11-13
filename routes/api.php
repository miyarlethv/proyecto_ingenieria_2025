<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    FundacionController,
    PersonaController,
    MascotaController,
    InicioSesionController,
    HistoriaClinicaController,
    RolController,
    PermisoController,
    FuncionarioController,
    AuthController
};

// LOGIN (funcionarios y fundaciones)
Route::post('login', [InicioSesionController::class, 'login'])->name('login');

// AUTH: obtener info del usuario autenticado y logout
Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
});

// Public: crear fundacion y personas (registro)
Route::post('crearFundacion', [FundacionController::class, 'crear']);
Route::get('Fundacion', [FundacionController::class, 'index']);
Route::get('traerPersonaIdFundacion', [FundacionController::class, 'traerPersonaId']);

// Personas públicas
Route::get('/', [PersonaController::class, 'index']);
Route::post('crear', [PersonaController::class, 'crear']);
Route::get('traerPersonaId', [PersonaController::class, 'traerPersonaId']);
Route::post('actualizar', [PersonaController::class, 'actualizar']);
Route::post('eliminarPorId', [PersonaController::class, 'eliminarPorId']);

// Rutas públicas de lectura de mascotas
Route::get('/mascotas/aleatorias', [MascotaController::class, 'aleatorias']);
Route::get('mascotas', [MascotaController::class, 'index']);

// ADMIN (solo Fundacion autenticada): crear/gestionar roles, permisos y funcionarios
Route::middleware(['auth:sanctum', \App\Http\Middleware\EnsureIsFundacion::class])->group(function () {
    // Fundacion puede actualizar o eliminar su propia entidad
    Route::post('actualizarFundacion', [FundacionController::class, 'actualizar']);
    Route::post('eliminarPorIdFundacion', [FundacionController::class, 'eliminarPorId']);

    // Roles
    Route::post('CrearRol', [RolController::class, 'store']);
    Route::get('ListarRoles', [RolController::class, 'index']);
    Route::put('ActualizarRol', [RolController::class, 'actualizar']);
    Route::put('EliminarRol', [RolController::class, 'eliminar']);

    // Permisos
    Route::post('CrearPermiso', [PermisoController::class, 'store']);
    Route::get('ListarPermisos', [PermisoController::class, 'index']);
    Route::put('ActualizarPermiso', [PermisoController::class, 'actualizar']);
    Route::put('EliminarPermiso', [PermisoController::class, 'eliminar']);

    // Funcionarios
    Route::get('ListarFuncionarios', [FuncionarioController::class, 'index']);
    Route::post('CrearFuncionario', [FuncionarioController::class, 'store']);
    Route::put('ActualizarFuncionario', [FuncionarioController::class, 'actualizar']);
    Route::put('EliminarFuncionario', [FuncionarioController::class, 'eliminar']);
});

// RUTAS PARA FUNCIONARIOS AUTENTICADOS (permisos validados en controlador)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('CrearMascotas', [MascotaController::class, 'crear']);
    Route::post('ActualizarMascotas', [MascotaController::class, 'actualizar']);
    Route::post('EliminarMascotas', [MascotaController::class, 'eliminar']);

    Route::middleware('can:crear historia')->post('CrearHistoriaClinica', [HistoriaClinicaController::class, 'crear']);
    Route::middleware('can:editar historia')->post('ActualizarHistoriaClinica', [HistoriaClinicaController::class, 'actualizar']);
    Route::middleware('can:eliminar historia')->post('EliminarHistoriaClinica', [HistoriaClinicaController::class, 'eliminar']);
    Route::get('ListarHistoriasClinicas', [HistoriaClinicaController::class, 'index']);
});
