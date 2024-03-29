<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Follow;
use App\Models\Profile;
use App\Models\UserPost;
use Barryvdh\Debugbar\Facades\Debugbar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class searchController extends Controller
{   

    //vista para mostrar las busquedas de posteos
    public function showSearchs(Request $request){
        try{
            $query = $request->query("q");
            if(!$query){
                return redirect()->route("showExplore");
            }

            //obtenemos ciertos datos de la aunteticacion del usuario
            $user = Auth::user();
            $name = $user->Name;
            $nickname =  $user->PersonalData->Nickname;
            $profilePhoto = [$user->Profile->ProfilePhotoURL,$user->Profile->ProfilePhotoName];
            $follows = (new Follow())->getCountFollows($user->UserID);
            $followers = (new Follow())->getCountFollowers($user->UserID);

            return view("app.explore.search.search",compact(["name","nickname","profilePhoto","follows","followers","query"]));
        }
        catch(\Exception $e){
            return $e->getMessage();
            return redirect()->route("errorPage");
        }
    }

    //obtenemos la informacion buscada por parte del usuario
    public function getSearchsData(Request $request){
        try{
            $authUserID = Auth::user()->UserID;

            //query params de la busqueda,usuario y comentarios
            $query = $request->query("q");
            $filterUser = $request->query("u");
            $filterComments = $request->query("c");


            if(!$query){
                throw new \Exception(json_encode(["redirect"=>route("errorPage"),"code"=>404]));
            }
            
            //en caso de que se deseen buscar usuarios
            if($query && $filterUser == true){
                return (new Profile())->getProfiles($query,$authUserID);
            }

            if($query && $filterComments == "true"){
                return (new Comment())->getComments($query,$authUserID);
            }

            //obtenemos los posteos
            return (new UserPost())->getPosts($query,$authUserID);
        }
        catch(\Exception $e){
            //obtenemos el mensaje y el codigo de estado
            $errorData = json_decode($e->getMessage(), true);

            if(isset($errorData["redirect"])){
                return response()->json(["redirect"=>$errorData["redirect"]],$errorData["code"]);
            }
            return response()->json(["errors"=>$e->getMessage()],500);
        }
    }
}
