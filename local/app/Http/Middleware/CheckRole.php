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
    public function handle(Request $request, Closure $next, $role): Response
    {
        if(empty(session('userdata'))){
            return redirect('/');
        }
        else if (session('userdata')->role !== $role) {
            // Jika tidak sesuai, redirect atau tampilkan pesan error
            return redirect('/unauthorized');
        }
        return $next($request);
    }
}
