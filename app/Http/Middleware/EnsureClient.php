<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClient
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->role || strtolower($user->role->nom) !== 'client') {
            abort(403, 'Acces reserve aux clients.');
        }

        return $next($request);
    }
}
