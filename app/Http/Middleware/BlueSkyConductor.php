<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlueSkyConductor
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('blue_sky_access_jwt')) {
            return redirect(route('welcome'));
        }
        $account = json_decode(session()->get('account', '{}'), true);
        $handle  = $account['handle'] ?? '';
        if (empty(\App\Models\InviteCode::CONDUCTORS_MAP[$handle])) {
            return redirect(route('welcome'));
        }

        return $next($request);
    }
}