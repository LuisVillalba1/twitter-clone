<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RemoveExtraSLashesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {    
        //Veficiamos que la uri al final contenga una barra y que la uri sea mayor a uno
        if (substr($request->getRequestUri(), -1) === '/' && strlen($request->getRequestUri()) > 1) {
            //las barras de la uri y redirigimos al usuario a la ruta correspondiente
            return redirect(rtrim($request->getRequestUri(), '/'), 301);
        }

        return $next($request);
    
    }
}
