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
    ProductoController,
    CategoriaController,
    NombreController,
    AuthController
};

// LOGIN

Route::post('login', [InicioSesionController::class, 'login']);

// RUTAS PÚBLICAS - Productos
Route::get('productos', [ProductoController::class, 'index']);
Route::post('CrearProducto', [ProductoController::class, 'store']);
Route::post('ActualizarProducto', [ProductoController::class, 'update']);
Route::post('EliminarProducto', [ProductoController::class, 'destroy']);


Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/nombres', [CategoriaController::class, 'getNombres']);
Route::post('/CrearCategoria', [CategoriaController::class, 'crearCategoria']);
Route::post('/CrearNombre', [CategoriaController::class, 'crearNombre']);
Route::post('/ActualizarCategoria', [CategoriaController::class, 'actualizarCategoria']);
Route::post('/ActualizarNombre', [CategoriaController::class, 'actualizarNombre']);
Route::post('/EliminarCategoria', [CategoriaController::class, 'eliminarCategoria']);
Route::post('/EliminarNombre', [CategoriaController::class, 'eliminarNombre']);

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

// Ruta pública para obtener funcionarios con roles (para formularios)
Route::get('ListarFuncionariosConRoles', [FuncionarioController::class, 'listarConRoles']);

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

    // Funcionarios (solo fundación puede gestionarlos)
    Route::get('ListarFuncionarios', [FuncionarioController::class, 'index']);
    Route::post('CrearFuncionario', [FuncionarioController::class, 'store']);
    Route::put('ActualizarFuncionario', [FuncionarioController::class, 'actualizar']);
    Route::put('EliminarFuncionario', [FuncionarioController::class, 'eliminar']);
});

// RUTAS PARA FUNCIONARIOS Y FUNDACIONES AUTENTICADOS (permisos validados en controlador)
Route::middleware('auth:sanctum')->group(function () {
    // Mascotas
    Route::post('CrearMascotas', [MascotaController::class, 'crear']);
    Route::post('ActualizarMascotas', [MascotaController::class, 'actualizar']);
    Route::post('EliminarMascotas', [MascotaController::class, 'eliminar']);

    // Historias clínicas
    Route::post('CrearHistoriaClinica', [HistoriaClinicaController::class, 'crear']);
    Route::put('ActualizarHistoriaClinica', [HistoriaClinicaController::class, 'actualizar']);
    Route::put('EliminarHistoriaClinica', [HistoriaClinicaController::class, 'eliminar']);
    Route::get('ListarHistoriasClinicas', [HistoriaClinicaController::class, 'index']);
});