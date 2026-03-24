<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next, string $role = 'admin')
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Admin can do everything, staff only limited access
        if ($role === 'staff' && !in_array($user->role, ['admin', 'staff'])) {
            return response()->json(['message' => 'Forbidden. Akses ditolak.'], 403);
        }

        if ($role === 'admin' && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden. Hanya admin yang bisa mengakses ini.'], 403);
        }

        return $next($request);
    }
}
