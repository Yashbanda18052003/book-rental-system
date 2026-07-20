<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // ❌ not logged in → go login
        if (!auth()->check()) {
            return redirect('/login');
        }

        // ❌ not admin → redirect instead of 403 error page
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard')
                ->with('error', 'Unauthorized access');
        }

        return $next($request);
    }
}