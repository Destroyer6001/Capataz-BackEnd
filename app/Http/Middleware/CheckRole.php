<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        if(Auth::check() && Auth::user()->hasRole($role))
        {
            return $next($request);
        }

        return response()->json(['message' => 'No tienes permiso para acceder a la ruta.'],403);
    }
}
