<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnsureIsFundacion
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            Log::info('EnsureIsFundacion: request without user', ['path' => $request->path(), 'headers' => [$request->header('authorization')]]);
            return response()->json(['message' => 'No autenticado. Se requiere token de fundación.'], 401);
        }


        // Permitir si el usuario es una Fundacion
        if ($user instanceof \App\Models\Fundacion) {
            Log::info('EnsureIsFundacion: user is Fundacion', ['id' => $user->id, 'email' => $user->email ?? null]);
            return $next($request);
        }

        // Permitir también si el usuario es un Funcionario con rol 'admin'
        if ($user instanceof \App\Models\Funcionario) {
            try {
                Log::info('EnsureIsFundacion: user is Funcionario', ['id' => $user->id, 'email' => $user->email ?? null, 'roles' => method_exists($user, 'getRoleNames') ? $user->getRoleNames() : null]);
                if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                    Log::info('EnsureIsFundacion: funcionario has admin role, allowing');
                    return $next($request);
                }
            } catch (\Exception $e) {
                // en caso de error con Spatie, denegar
                Log::error('EnsureIsFundacion: error checking role', ['exception' => $e->getMessage()]);
                return response()->json(['message' => 'Forbidden: error al verificar rol'], 403);
            }
        }

    // Si no es fundacion ni funcionario admin -> denegar
    return response()->json(['message' => 'Forbidden: requiere token de fundación o funcionario admin'], 403);
    }
}
