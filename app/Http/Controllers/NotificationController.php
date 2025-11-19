<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    // Obtener notificaciones del usuario autenticado
    public function index(Request $request)
    {
        $user = $request->user();
        $notificaciones = Notification::where('persona_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($notificaciones);
    }

    // Marcar una notificación como leída
    public function marcarLeida(Request $request, $id)
    {
        $user = $request->user();
        $notificacion = Notification::where('id', $id)
            ->where('persona_id', $user->id)
            ->firstOrFail();
        $notificacion->read = true;
        $notificacion->save();
        return response()->json(['success' => true]);
    }
}
