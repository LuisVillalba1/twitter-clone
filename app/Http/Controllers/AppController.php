<?php

namespace App\Http\Controllers;

use App\Events\notiTest;
use App\Http\Requests\UserPost\NewPostRequest;
use App\Models\MultimediaPost;
use App\Models\PersonalData;
use App\Models\UserPost;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use function PHPSTORM_META\type;

class AppController extends Controller
{
    //mostramos la main ap
    public function show(){
        try{
            //obtenemos ciertos datos de la aunteticacion del usuario
            $user = Auth::user();
            $name = $user->Name;
            $nickname =  $user->PersonalData->Nickname;
            $profilePhoto = [$user->Profile->ProfilePhotoURL,$user->Profile->ProfilePhotoName];

            return view("app.main",compact(["name","nickname","profilePhoto"]));
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

    public function getPostsVisualizated(){
        try{
            return (new UserPost())->getPostsVisualized();
        }
        catch(\Exception $e){
            return response()->json(["errors"=>$e->getMessage()],500);
        }
    }
}

