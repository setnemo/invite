<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlueSkyConductor
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('blue_sky_access_jwt')) {
            return redirect(route('welcome'));
        }
        $account = json_decode($request->session()->get('account', '{}'), true);
        $handle  = $account['handle'] ?? '';
        if (empty(\App\Models\InviteCode::CONDUCTORS_MAP[$handle])) {
            dd(2);
            return redirect(route('welcome'));
        }

        return $next($request);
    }
}
