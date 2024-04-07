<?php

namespace App\Http\Controllers;

use App\Events\notiTest;
use App\Http\Requests\UserPost\NewPostRequest;
use App\Models\Follow;
use App\Models\MultimediaPost;
use App\Models\PersonalData;
use App\Models\UserPost;
use Barryvdh\Debugbar\Facades\Debugbar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;

use function PHPSTORM_META\type;

class AppController extends Controller
{   
    //eliminamos la session
    public function deleteSession(){
        try{
            Auth::logout();

            return route("main");
        }
        catch(\Exception $e){
            return response()->json(["errors"=>$e->getMessage()]);
        }
    }

    //mostramos la main ap
    public function show(Request $request){
        try{
            //obtenemos ciertos datos de la aunteticacion del usuario
            $user = Auth::user();
            $name = $user->Name;
            $nickname =  $user->PersonalData->Nickname;
            $profilePhoto = [$user->Profile->ProfilePhotoURL,$user->Profile->ProfilePhotoName];
            $follows = (new Follow())->getCountFollows($user->UserID);
            $followers = (new Follow())->getCountFollowers($user->UserID);

            //obtenemos la fecha en la que se visito el link y creamos una cookie con este valor
            $now = now()->format('Y-m-d H:i:s');
            $cookie = Cookie::forever("lastHomeVisited",$now);

            return response()->view("app.main",compact(["name","nickname","profilePhoto","follows","followers"]))->withCookie($cookie);
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }

    //mostramos la vista para explorar
    public function showExplore(){
        try{
            //obtenemos ciertos datos de la aunteticacion del usuario
            $user = Auth::user();
            $name = $user->Name;
            $nickname =  $user->PersonalData->Nickname;
            $profilePhoto = [$user->Profile->ProfilePhotoURL,$user->Profile->ProfilePhotoName];
            $follows = (new Follow())->getCountFollows($user->UserID);
            $followers = (new Follow())->getCountFollowers($user->UserID);

            return view("app.explore.explore",compact(["name","nickname","profilePhoto","follows","followers"]));
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }

    //mostramos la vista para crear un nuevo post
    public function showCreatePost(){
        //obtenemos el nombre y la imagen en caso de que contenga del usuario autenticado
        $user = Auth::user();
        $nickname =  $user->PersonalData->Nickname;
        $profilePhoto = [$user->Profile->ProfilePhotoURL,$user->Profile->ProfilePhotoName];
        
        return view("app.posts.createPost",compact(["nickname","profilePhoto"]));
    }

    //creamos un nuevo post
    public function createPost(NewPostRequest $request){
        try{
            //obtenemos las imagenes subidas y el usuaio autenticado
            $images = $request->file('images');
            $user = Auth::user();
    
            //obtenemos el id del nuevo post
            $newPostID = (new UserPost())->createPost($user,$request->message);

            //en caso de que el usuario haya subido imagenes las imagenes al post nuevo
            if($images){
                (new MultimediaPost())->createMultimediaPost($images,$newPostID);
            }
            return redirect()->route("mainApp")->getTargetUrl();
        }
        catch(\Exception $e){
            return response()->json(["errors"=>"Ha ocurrido un error al realizar la publicacion, por favor intentelo mas tarde"],500);
        }
    }

    //obtenemos los post de los usuarios que aun no han sido visualizados por el usuario autenticado
    public function getUsersPosts(){
        try{
            return (new UserPost())->getAllPublics();
        }
        catch(\Exception $e){
            return response()->json(["error"=>"Ha ocurrido un error al obtener las publicaciones"],500);
        }
    }

    //obtenemos los posteos visualizados
    public function getPostsVisualizated(Request $request){
        try{
            //en caso de que no exista la cookie de la ultima visita a la vista home lanzamos una exepcion
            //ya que esta nos permitira no mostrar posteos repetidos
            if(!$request->cookie("lastHomeVisited")){
                throw new Exception();
            }
            $lastVisited = $request->cookie("lastHomeVisited");
            //obtenemos aquellos posteos visualizados por parte del usuario autenticado que sean inferiores a la ultima visita de la ruta home
            return (new UserPost())->getPostsVisualized($lastVisited);
        }
        catch(\Exception $e){
            return response()->json(["errors"=>$e->getMessage()],500);
        }
    }

}

