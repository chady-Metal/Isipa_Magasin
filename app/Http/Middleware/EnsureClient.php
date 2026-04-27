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

        if (! $user || ! $user->isClient()) {
            abort(403, 'Acces reserve aux clients.');
        }

        $user->forceFill(['last_seen_at' => now()])->save();

        return $next($request);
    }
}
