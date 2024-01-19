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
        $initSession = route("siging");
        return view("access.main",compact("initSession"));
    }

    public function session(){
        $routeMain = route("main");
        return view("access.initSession",compact("routeMain"));
    }

    public function initSession(InitSessionRequest $request){
        try{
        //find user
        $user = User::where(function($query) use ($request){
            //find user for his email
            $query->where("Email",$request->input("user"))
            //or find user for his nickname
                    ->orWhereHas("personalData",function($query) use ($request){
                        $query->where("Nickname",$request->input("user"));
                    });
        })->first();
        $password = $user->Password;

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