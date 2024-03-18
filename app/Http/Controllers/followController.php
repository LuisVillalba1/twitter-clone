<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\PersonalData;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class followController extends Controller
{   
    //seguimos a un usuario
    public function followUser($username){
        try{
            //verifiicamos que exista el usuario y que sea distinto al usuario autenticado
            (new PersonalData())->checkUsername($username);
            if($username == Auth::user()->PersonalData->Nickname){
                throw new Exception("Te estas autosiguiendo");
            }
            return response()->json(["message"=>(new Follow())->followOrUnfollow($username)]);
        }
        catch(\Exception $e){
            return response()->json(["erros",$e->getMessage()],500);
        }
    }
}
