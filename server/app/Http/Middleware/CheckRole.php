<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $roleRequis) 
    {
        if (Gate::denies($roleRequis)) {
            // L'utilisateur n'a pas le rôle requis pour cette action, vous pouvez rediriger ou retourner une réponse d'erreur
            return abort(403, 'Accès refusé');
        }

        // L'utilisateur a le rôle requis, continuez vers la route
        return $next($request);
    }
}


        