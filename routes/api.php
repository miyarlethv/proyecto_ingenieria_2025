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
    AuthController,
    SolicitudAdopcionController
};

// LOGIN

Route::post('login', [InicioSesionController::class, 'login'])->name('login');
// RUTAS PÚBLICAS - Lectura de productos, categorías y nombres
Route::get('productos', [ProductoController::class, 'index']);
Route::get('categorias', [CategoriaController::class, 'index']);
Route::get('nombres', [CategoriaController::class, 'getNombres']);



// AUTH: obtener info del usuario autenticado y logout
Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    // Notificaciones del usuario autenticado
    Route::get('notificaciones', [App\Http\Controllers\NotificationController::class, 'index']);
    Route::post('notificaciones/{id}/marcar-leida', [App\Http\Controllers\NotificationController::class, 'marcarLeida']);
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

    // Solicitudes de adopción
    Route::post('solicitudes-adopcion', [SolicitudAdopcionController::class, 'store']); // Crear solicitud
    Route::get('solicitudes-adopcion', [SolicitudAdopcionController::class, 'index']); // Listar solicitudes
    Route::get('solicitudes-adopcion/mis-solicitudes', [SolicitudAdopcionController::class, 'misSolicitudes']); // Mis solicitudes
    Route::get('solicitudes-adopcion/{id}', [SolicitudAdopcionController::class, 'show']); // Ver detalle
    Route::post('solicitudes-adopcion/{id}/estado', [SolicitudAdopcionController::class, 'updateEstado']); // Aprobar/Rechazar
    Route::post('solicitudes-adopcion/{id}/eliminar', [SolicitudAdopcionController::class, 'destroy']); // Eliminar solicitud

    // Productos (autenticados)
    Route::post('CrearProducto', [ProductoController::class, 'store']);
    Route::post('ActualizarProducto', [ProductoController::class, 'update']);
    Route::post('EliminarProducto', [ProductoController::class, 'destroy']);

    // Categorías (autenticados)
    Route::post('CrearCategoria', [CategoriaController::class, 'crearCategoria']);
    Route::post('ActualizarCategoria', [CategoriaController::class, 'actualizarCategoria']);
    Route::post('EliminarCategoria', [CategoriaController::class, 'eliminarCategoria']);

    // Nombres de productos (autenticados)
    Route::post('CrearNombre', [CategoriaController::class, 'crearNombre']);
    Route::post('ActualizarNombre', [CategoriaController::class, 'actualizarNombre']);
    Route::post('EliminarNombre', [CategoriaController::class, 'eliminarNombre']);
});