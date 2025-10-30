<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ForceHttpsFromProxy
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('x-forwarded-proto') === 'https') {
            URL::forceScheme('https');
        }

        return $next($request);
    }
}
