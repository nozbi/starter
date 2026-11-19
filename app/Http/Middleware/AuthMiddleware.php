<?php

namespace App\Http\Middleware;

use App\App;
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
        if ($request->is(...App::getGuestRoutes())) 
        {
            return $next($request);
        }
        return redirect()->route('login');
    }
}