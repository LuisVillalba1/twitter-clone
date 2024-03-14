<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\PersonalData;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class followController extends Controller
{
    public function followUser($username){
        try{
            (new PersonalData())->checkUsername($username);
            if($username == Auth::user()->PersonalData->Nickname){
                throw new Exception("Te estas autosiguiendo");
            }
            return response()->json(["message"=>(new Follow())->followOrUnfollow($username)]);
        }
        catch(\Exception $e){
            return response()->json(["erros","Ha ocurrido un error al realizar la solicitud"],500);
        }
    }
}
