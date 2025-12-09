<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ManagerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему.');
        }

        if (!Auth::user()->isManager()) {
            return redirect()->route('home')->with('error', 'У вас нет доступа к этой странице.');
        }

        return $next($request);
    }
}