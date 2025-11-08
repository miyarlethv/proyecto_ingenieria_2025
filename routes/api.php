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
    FuncionarioController
};

// LOGIN

Route::post('login', [InicioSesionController::class, 'login']);


// RUTAS PROTEGIDAS

Route::middleware('auth:sanctum')->group(function () {

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

    // Mascotas (solo si tiene permiso)
    Route::middleware('can:crear mascotas')->post('CrearMascotas', [MascotaController::class, 'crear']);
    Route::get('mascotas', [MascotaController::class, 'index']);
    Route::middleware('can:editar mascotas')->put('ActualizarMascotas', [MascotaController::class, 'actualizar']);
    Route::middleware('can:eliminar mascotas')->put('EliminarMascotas', [MascotaController::class, 'eliminar']);

    // Historias clínicas
    Route::middleware('can:crear historia')->post('CrearHistoriaClinica', [HistoriaClinicaController::class, 'crear']);
    Route::get('ListarHistoriasClinicas', [HistoriaClinicaController::class, 'index']);
    Route::middleware('can:editar historia')->put('ActualizarHistoriaClinica', [HistoriaClinicaController::class, 'actualizar']);
    Route::middleware('can:eliminar historia')->put('EliminarHistoriaClinica', [HistoriaClinicaController::class, 'eliminar']);
    Route::get('/mascotas/aleatorias', [MascotaController::class, 'aleatorias']);
    
    // Roles
    Route::middleware('role:admin')->group(function () {
        Route::post('CrearRol', [RolController::class, 'store']);
        Route::get('ListarRoles', [RolController::class, 'index']);
        Route::put('ActualizarRol', [RolController::class, 'actualizar']);
        Route::put('EliminarRol', [RolController::class, 'eliminar']);
    });

    // Permisos
    Route::middleware('role:admin')->group(function () {
        Route::post('CrearPermiso', [PermisoController::class, 'store']);
        Route::get('ListarPermisos', [PermisoController::class, 'index']);
        Route::put('ActualizarPermiso', [PermisoController::class, 'actualizar']);
        Route::put('EliminarPermiso', [PermisoController::class, 'eliminar']);
    });

    // Funcionarios
    Route::middleware('role:admin')->group(function () {
        Route::get('ListarFuncionarios', [FuncionarioController::class, 'index']);
        Route::post('CrearFuncionario', [FuncionarioController::class, 'store']);
        Route::put('ActualizarFuncionario', [FuncionarioController::class, 'actualizar']);
        Route::put('EliminarFuncionario', [FuncionarioController::class, 'eliminar']);
    });
});
