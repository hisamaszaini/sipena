<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next, ...$roles)
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    $userRole = auth()->user()->role;
    
    foreach ($roles as $role) {
        if ($userRole === $role) {
            return $next($request);
        }
    }

    // return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    
    abort(403, 'Anda tidak memiliki akses ke halaman ini.');
}
}
