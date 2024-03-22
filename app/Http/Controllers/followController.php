<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\PersonalData;
use Exception;
use Illuminate\Support\Facades\Auth;

class followController extends Controller
{   
    //seguimos a un usuario
    public function followUser($username){
        try{
            $authUser = Auth::user();
            //verifiicamos que exista el usuario y que sea distinto al usuario autenticado
            (new PersonalData())->checkUsername($username);
            if($username == $authUser->PersonalData->Nickname){
                throw new \Exception();
            }
            return response()->json(["message"=>(new Follow())->followOrUnfollow($username,$authUser->UserID)]);
        }
        catch(\Exception $e){
            $errorData = json_decode($e->getMessage(), true);

            //si no se encontro el usuario mandamos un error 404
            if(isset($errorData["redirect"])){
                return response()->json(["errors"=>"No se ha encontrado el usuario"],$errorData["code"]);
            }

            return response()->json(["errors"=>"Ha ocurrido un error al realizar la solicitud"],500);
        }
    }
}
