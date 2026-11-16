<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check())
        {
            return $next($request);
        }
        if ($request->is('home', 'login', 'register', 'two-factor', 'two-factor.login', 'password', 'password.request', 'password.reset')) 
        {
            return $next($request);
        }
        return redirect()->route('login');
    }
}