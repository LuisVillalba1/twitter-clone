<?php

namespace App\Http\Controllers;

use App\Custom\CookieSearch;
use App\Models\PersonalData;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Cookie as symfornYCookie;

class CookiesController extends Controller
{   
    //verificamos si existe el valor de la cookie y agregamos el valor al pricipio
    public function deleteRepeatValue($userData,$type,$cookie){
        foreach($cookie as $key =>$value){
            if($value->type == $type && $value->value == $userData){
                //eliminamos la cookie existente y la retornamos
                array_splice($cookie,$key,1);
                return $cookie;
            }
        }
        return false;
    }

    //la cookie va a ser un array menor a 10 espacio
    public function checkLengthCookie($cookie){
        if(count($cookie) > 9){
            return false;
        }

        return true;
    }

    //seteamos los valores en la cookie userSearch
    public function setCookieSearch($userData,$type,$request){

        //en caso de que no exista la cookie alamacenamos en la cookie la busqueda
        if(!$request->hasCookie("userSearch")){
            $cookieValue = [new CookieSearch($type,$userData)];
            $cookie = Cookie::forever("userSearch",serialize($cookieValue));

            return $cookie;
        }


        //si ya existe la cookie agregamos al array el objeto nuevo,y guardamos la cookie

        $cookieSearchValue = unserialize($request->cookie("userSearch"));


        //obtenemos la nueva cookie en caso de que se haya eliminado un nuevo valor
        $cookieRepeat = $this->deleteRepeatValue($userData,$type,$cookieSearchValue);


        if($cookieRepeat != false && count($cookieRepeat) >= 0){
            $cookieSearchValue = $cookieRepeat;
        }

        //agregamos el nuevo objeto al array
        array_unshift($cookieSearchValue,new CookieSearch($type,$userData));

        //en caso de que el array tenga un largo mayor a 9 eliminamos el ultimo elemento de este
        if(!$this->checkLengthCookie($cookieSearchValue)){
            array_pop($cookieSearchValue);
        }

        //guardamos y retornamos la cookie
        $cookie = Cookie::forever("userSearch",serialize($cookieSearchValue));


        return $cookie;
    }

    //vamos a obtener todos los valores de las cookie que sean usuario,y retornamos un array de estos
    public function getUsers($cookie){
        $cookieUser = [];
        foreach($cookie as $value){
            if($value->type == "User"){
                array_push($cookieUser,$value->value);
            }
        }

        return $cookieUser;
    }

    //obtenemos la informacion de los usuarios
    public function getUserDatas($cookie){
        $userData = PersonalData::
        select("PersonalDataID","Nickname")
        ->with([
            "user"=>function ($queryUser){
                $queryUser->select("UserID","PersonalDataID","Name")
                ->with([
                    "Profile"=>function ($queryProfile){
                        $queryProfile->select("ProfileID","UserID","ProfilePhotoURL","ProfilePhotoName");
                    }
                ]);
            }
        ])
        ->whereIn("Nickname",$cookie)
        ->get();

        return $userData;
    }

    //c
    public function addCorrectUserData($cookie,$cookieDataUsers){
        //recorremos la cookie y toda la informacion de los usuario 
        foreach($cookie as $valueCookie){
            foreach($cookieDataUsers as $valueUserData){
                //en caso de que el tipo sea User y los valores concuerden agregamos al valor de la cookie la informacion del usuario
                if(($valueCookie->type == "User") && ($valueCookie->value == $valueUserData->Nickname)){
                    $valueCookie->value = $valueUserData;
                    $valueCookie->value["Link"] = route("showProfile",["username"=>$valueUserData->Nickname]);
                }
            }
        }

        return $cookie;
    }

    //obtenemos las busquedas recientes del usuario,las cuales se almacenan en la cookie userSearch
    public function getRecentSearchs(Request $request){
        try{
            $cookie = Cookie::get("userSearch");
            if($cookie){
                $cookieData = unserialize($cookie);
                //obtenemos todas aquellas valores de la cookie las cuales sean busquedas de usuarios y luego
                //obtenemos la informacion correspondiente de cada uno de estos
                $cookieUser = $this->getUsers($cookieData);
                $cookieDataUsers = $this->getUserDatas($cookieUser);

                //agregamos la informacion en concreto para la cookie
                $cookieData = $this->addCorrectUserData($cookieData,$cookieDataUsers);

                return response()->json(["data"=>$cookieData]);
            }
            return response()->json(["errors"=>"Cookie no encontrada"],404);
        }
        catch(\Exception $e){
            return response()->json(["errors"=>$e->getMessage()],500);
        }
    }

    public function deleteSearchs(Request $request){
        $cookie = Cookie::get("userSearch");
        if($cookie){
            return response(true)->withCookie(Cookie::forget("userSearch"));
        }
        return false;
    }
}
