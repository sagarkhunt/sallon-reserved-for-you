<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ServiceAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user() && Auth::user()->hasRole('service') ) {
            return $next($request);
        }

        Auth::logout();
        return redirect('service-provider/login');
    }
}
