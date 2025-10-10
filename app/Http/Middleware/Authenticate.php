<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // Cek route yang diakses untuk menentukan redirect yang tepat
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }
            
            // Untuk user biasa, redirect ke login page
            return route('login');
        }
        
        return null;
    }
}