<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class SteamLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        return Auth::guest() ? redirect()->guest('/login')->withErrors(['error' => 'Please sign in through Steam first in order to access this page.']) : $next($request);
    }
}
