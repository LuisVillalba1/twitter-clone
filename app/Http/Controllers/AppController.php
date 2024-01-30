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
    public function show(){
        return view("app.main");
    }

    public function showCreatePost(){
        return view("app.posts.createPost");
    }

    public function createPost(NewPostRequest $request){
        try{
            $images = $request->file('images');
            $user = Auth::user();
    
            $newPostID = (new UserPost())->createPost($user,$request->message);
    
            if($images){
                (new MultimediaPost())->createMultimediaPost($images,$newPostID);
            }
            return "facts";
        }
        catch(\Exception $e){
            return response()->json(["error"=>$e->getMessage()],500);
        }
    }

    public function getUsersPosts(){
        try{
            return (new UserPost())->getAllPublics();
        }
        catch(\Exception $e){
            return response()->json(["error",$e->getMessage()],500);
        }
    }
}

