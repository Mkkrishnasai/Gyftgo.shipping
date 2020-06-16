<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $userRole = Auth::user()->roles->pluck('name');
        if(!$userRole->contains('ROLE_SUPERADMIN'))
        {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}
