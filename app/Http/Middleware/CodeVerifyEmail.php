<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CodeVerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        //no permitimos ingresar al usuario en caso de que no se haya enviado el email para verificar la cuenta
        if($request->session()->get("sendCode")){
            return $next($request);
        }
        return redirect()->route("settings");
    }
}
