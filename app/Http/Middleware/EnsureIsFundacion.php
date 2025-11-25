<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Fundacion;
use App\Models\Funcionario;

class EnsureIsFundacion
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        Log::info('🔍 EnsureIsFundacion: INICIO', [
            'path' => $request->path(),
            'auth_header' => $request->header('authorization'),
            'user' => $user ? get_class($user).':'.$user->id : 'NULL',
        ]);

        if (! $user) {
            Log::warning('⚠️ EnsureIsFundacion: request without user', ['path' => $request->path()]);
            return response()->json(['message' => 'No autenticado. Se requiere token de fundación.'], 401);
        }

        if ($user instanceof Fundacion) {
            Log::info('EnsureIsFundacion: user is Fundacion', ['id' => $user->id, 'email' => $user->email ?? null]);
            return $next($request);
        }

        if ($user instanceof Funcionario) {
            try {
                Log::info('EnsureIsFundacion: user is Funcionario', [
                    'id' => $user->id,
                    'email' => $user->email ?? null,
                    'roles' => method_exists($user, 'getRoleNames') ? $user->getRoleNames() : null
                ]);
                if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                    Log::info('EnsureIsFundacion: funcionario has admin role, allowing');
                    return $next($request);
                }
            } catch (\Exception $e) {
                Log::error('EnsureIsFundacion: error checking role', ['exception' => $e->getMessage()]);
                return response()->json(['message' => 'Forbidden: error al verificar rol'], 403);
            }
        }

        // Si no es fundacion ni funcionario admin -> denegar
        return response()->json(['message' => 'Forbidden: requiere token de fundación o funcionario admin'], 403);
    }
}