<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlueSky
{
    public function handle(Request $request, Closure $next)
    {

        if (!$request->session()->has('password_hash_web')) {
            return route('welcome');
        }

        return $next($request);
    }
}
