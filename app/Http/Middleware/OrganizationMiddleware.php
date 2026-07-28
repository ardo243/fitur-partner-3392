<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('organization_id')) {
            return redirect()->route('login')->with('error', 'Silakan masuk ke akun organisasi Anda terlebih dahulu.');
        }

        return $next($request);
    }
}
