<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPost\NewPostRequest;
use App\Models\MultimediaPost;
use App\Models\UserPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use function PHPSTORM_META\type;

class AppController extends Controller
{
    //mostramos la main ap
    public function show(){
        return view("app.main");
    }

    //mostramos la vista para crear un nuevo post
    public function showCreatePost(){
        return view("app.posts.createPost");
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
            return "facts";
        }
        catch(\Exception $e){
            return response()->json(["error"=>$e->getMessage()],500);
        }
    }

    //obtenemos los post de los usuarios
    public function getUsersPosts(){
        try{
            return (new UserPost())->getAllPublics();
        }
        catch(\Exception $e){
            return response()->json(["error",$e->getMessage()],500);
        }
    }
}

