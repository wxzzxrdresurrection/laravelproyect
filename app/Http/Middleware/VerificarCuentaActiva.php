<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarCuentaActiva
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->user()->active == 0)
        abort(403,"La cuenta no está activa");

        return $next($request);
    }
}
