<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlueSky
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('blue_sky_access_jwt')) {
            return redirect(route('welcome'));
        }
        if (config('app.under_test', false)) {
            $account = json_decode(session()->get('account', '{}'), true);
            if (in_array($account['handle'] ?? '', \App\Models\InviteCode::TESTERS)) {
                return $next($request);
            }
            return redirect(route('welcome'));
        }

        return $next($request);
    }
}
