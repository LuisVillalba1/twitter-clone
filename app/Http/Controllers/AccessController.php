<?php

namespace App\Http\Controllers;

use App\Http\Requests\InitSession\InitSessionRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\Throw_;

class AccessController extends Controller
{
    public function index(){
        //enviamos la vista en especifico con la ruta para iniciar session
        $initSession = route("siging");
        return view("access.main",compact("initSession"));
    }

    //mostramos la vista para iniciar session junto con la ruta main por si no quiere iniciar sesion el usuario
    public function session(){
        $routeMain = route("main");
        return view("access.initSession",compact("routeMain"));
    }

    //permitimos iniciar session al usuairo
    public function initSession(InitSessionRequest $request){
        try{
        //buscamos el usuario
        $user = User::where(function($query) use ($request){
            //buscamos al usuario por su Email
            $query->where("Email",$request->input("user"))
            //o lo buscamos por su nombre de usuario
                    ->orWhereHas("personalData",function($query) use ($request){
                        $query->where("Nickname",$request->input("user"));
                    });
        })->first();
        $password = $user->Password;

        //chequeamos que la contraseña ingresada sea la misma que posee el usuario
        if(Hash::check($request->input("password"),$password)){
            Auth::login($user);
            return route("mainApp");
        }
        throw new Exception("Incorrect password") ;       
        }
        catch(\Exception $e){
            return response()->json(["error"=>$e->getMessage()],500);
        }
    }
}