<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ((session('user.role') ?? null) !== 'administrateur') {
            abort(403);
        }

        return $next($request);
    }
}
