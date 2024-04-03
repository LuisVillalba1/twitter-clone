<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class setPasswordSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        //verificaos si el usuario ya ha ingresado anteriormente su contraseña y esta posea una
        $setPassword = $request->session()->get("passwordSet");
        if(!$setPassword && Auth::user()->Password){
            //guardamos la url a la cual se queria acceder
            $request->session()->put("nextUrl",$request->url());
            
            return redirect()->route("setPasswordView");
        }

        return $next($request);
    }
}
