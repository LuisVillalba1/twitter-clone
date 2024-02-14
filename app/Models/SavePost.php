<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SavePost extends Model
{
    use HasFactory;

    protected $primaryKey = "SaveID";

    public function PostID(){
        return $this->belongsTo(UserPost::class,"PostID");
    }

    //mostramos la vista de elementos guardados por parte del usuario
    public function showBookmarks(){
        return view("app.posts.bookmarks.bookmarks");
    }

    //obtenemos todos los post que ha guardado el usuario
    public function getBookmarks(){
        try{
            $user = Auth::user();

            $userID = $user->UserID;
            
            return SavePost::where("UserID",$userID);

        }
        catch(\Exception $e){
            return response()->json(["errors"=>"Ha ocurrido un error"],500);
        }
        
    }

    public function checkSavePost($postID){
        //obtenemos los datos del usuario authenticado
        $user = Auth::user();

        $userID = $user->UserID;

        $savePost = savePost::where("UserID",$userID)
        ->where("PostID",$postID)
        ->first();

        if(!$savePost){
            
            $newSavePost = new savePost();

            $newSavePost->UserID = $userID;
            $newSavePost->PostID = $postID;

            $newSavePost->save();

            return true;
        }

        savePost::where("UserID",$userID)
        ->where("PostID",$postID)
        ->delete();

        return false;
    }

    public function savePost(Request $request,$username,$encryptID){
        try{
            //verificamos si existe el post y el usuario 
            $postID = Crypt::decryptString($encryptID);

            (new UserPost())->checkPostID($postID);
    
            (new PersonalData())->checkUsername($username);

            return $this->checkSavePost($postID);
        }
        catch(\Exception $e){
            return response()->json("Ha ocurrido un error inesperado");
        }
    }

    public function sendLinkSave(){

    }
}
