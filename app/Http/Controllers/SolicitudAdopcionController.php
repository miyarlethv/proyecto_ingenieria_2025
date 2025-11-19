<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SolicitudAdopcion;
use App\Models\Mascota;

class SolicitudAdopcionController extends Controller
{
    /**
     * Crear una nueva solicitud de adopción
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mascota_id' => 'required|exists:mascotas,id',
            'edad' => 'required|integer|min:18',
            'ciudad_residencia' => 'required|string|max:255',
            'ocupacion' => 'required|string|max:255',
            'estrato_social' => 'required|string|max:50',
            'tiene_hijos' => 'required|in:Sí,No',
            'numero_personas_hogar' => 'required|integer|min:1',
            'acepta_seguimiento' => 'required|in:Sí,No',
        ]);

        // Obtener el ID de la persona autenticada
        $persona = $request->user();
        
        if (!$persona) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        // Verificar si ya existe una solicitud pendiente o aprobada para esta mascota por este usuario
        $solicitudExistente = SolicitudAdopcion::where('mascota_id', $validated['mascota_id'])
            ->where('persona_id', $persona->id)
            ->whereIn('estado', ['pendiente', 'aprobada'])
            ->first();

        if ($solicitudExistente) {
            $mensaje = $solicitudExistente->estado === 'pendiente' 
                ? 'Ya tienes una solicitud pendiente para esta mascota'
                : 'Ya tienes una solicitud aprobada para esta mascota';
            
            return response()->json(['message' => $mensaje], 400);
        }

        // Crear la solicitud
        $solicitud = SolicitudAdopcion::create([
            'mascota_id' => $validated['mascota_id'],
            'persona_id' => $persona->id,
            'edad' => $validated['edad'],
            'ciudad_residencia' => $validated['ciudad_residencia'],
            'ocupacion' => $validated['ocupacion'],
            'estrato_social' => $validated['estrato_social'],
            'tiene_hijos' => $validated['tiene_hijos'],
            'numero_personas_hogar' => $validated['numero_personas_hogar'],
            'acepta_seguimiento' => $validated['acepta_seguimiento'],
            'estado' => 'pendiente'
        ]);

        return response()->json([
            'message' => 'Solicitud de adopción enviada exitosamente',
            'data' => $solicitud->load('mascota')
        ], 201);
    }

    /**
     * Listar todas las solicitudes
     * - Usuarios normales: solo ven sus propias solicitudes
     * - Fundación/Funcionarios: ven todas las solicitudes
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Si es fundación o funcionario, puede ver todas las solicitudes
        if ($user instanceof \App\Models\Fundacion || $user instanceof \App\Models\Funcionario) {
            $solicitudes = SolicitudAdopcion::with(['mascota', 'persona'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Usuarios normales solo ven sus propias solicitudes
            $solicitudes = SolicitudAdopcion::with('mascota')
                ->where('persona_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return response()->json($solicitudes, 200);
    }

    /**
     * Ver detalle de una solicitud específica
     */
    public function show(Request $request, $id)
    {
        $solicitud = SolicitudAdopcion::with(['mascota', 'persona'])->findOrFail($id);
        $user = $request->user();

        // Verificar permisos: solo el solicitante o fundación/funcionarios pueden ver
        if (!($user instanceof \App\Models\Fundacion || $user instanceof \App\Models\Funcionario) 
            && $solicitud->persona_id !== $user->id) {
            return response()->json(['message' => 'No tienes permiso para ver esta solicitud'], 403);
        }

        return response()->json($solicitud, 200);
    }

    /**
     * Actualizar el estado de una solicitud (aprobar o rechazar)
     * Solo para fundación y funcionarios con permisos
     */
    public function updateEstado(Request $request, $id)
    {
        $validated = $request->validate([
            'estado' => 'required|in:aprobada,rechazada'
        ]);

        $user = $request->user();

        // Solo fundación o funcionarios pueden cambiar el estado
        if (!($user instanceof \App\Models\Fundacion || $user instanceof \App\Models\Funcionario)) {
            return response()->json(['message' => 'No tienes permiso para realizar esta acción'], 403);
        }

        // Validar permiso si es funcionario
        if ($user instanceof \App\Models\Funcionario) {
            $tienePermiso = $user->getAllPermissions()->contains(function ($permiso) {
                return $permiso->url === 'solicitudes-adopcion';
            });
            if (!$tienePermiso) {
                return response()->json(['message' => 'No tienes permiso para gestionar solicitudes de adopción'], 403);
            }
        }

        $solicitud = SolicitudAdopcion::with(['mascota', 'persona'])->findOrFail($id);

        if ($solicitud->estado !== 'pendiente') {
            return response()->json([
                'message' => 'Esta solicitud ya fue procesada anteriormente'
            ], 400);
        }

            // Crear notificación para el usuario según el estado
            if ($validated['estado'] === 'aprobada') {
                \App\Models\Notification::create([
                    'persona_id' => $solicitud->persona_id,
                    'title' => '¡Tu solicitud de adopción fue aprobada!',
                    'message' => 'La solicitud para adoptar a ' . ($solicitud->mascota->nombre ?? 'una mascota') . ' fue aprobada. La fundación se comunicará contigo al teléfono registrado.',
                ]);
            } elseif ($validated['estado'] === 'rechazada') {
                \App\Models\Notification::create([
                    'persona_id' => $solicitud->persona_id,
                    'title' => 'Tu solicitud de adopción fue rechazada',
                    'message' => 'La solicitud para adoptar a ' . ($solicitud->mascota->nombre ?? 'una mascota') . ' fue rechazada. Puedes comunicarte con la fundación para más información.',
                ]);
            }

            $solicitud->update(['estado' => $validated['estado']]);

        return response()->json([
            'message' => "Solicitud {$validated['estado']} exitosamente",
            'data' => $solicitud
        ], 200);
    }

    /**
     * Obtener solicitudes del usuario autenticado
     */
    public function misSolicitudes(Request $request)
    {
        $user = $request->user();

        $solicitudes = SolicitudAdopcion::with('mascota')
            ->where('persona_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($solicitudes, 200);
    }

    /**
     * Eliminar una solicitud (solo si está pendiente y es el solicitante)
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $solicitud = SolicitudAdopcion::findOrFail($id);

        // Solo el solicitante puede eliminar su propia solicitud
        if ($solicitud->persona_id !== $user->id) {
            return response()->json(['message' => 'No tienes permiso para eliminar esta solicitud'], 403);
        }

        // Solo se pueden eliminar solicitudes pendientes
        if ($solicitud->estado !== 'pendiente') {
            return response()->json([
                'message' => 'No puedes eliminar una solicitud que ya fue procesada'
            ], 400);
        }

        $solicitud->delete();

        return response()->json(['message' => 'Solicitud eliminada exitosamente'], 200);
    }
}
