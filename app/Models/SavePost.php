<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use PhpParser\Node\Expr\Throw_;

class SavePost extends Model
{
    use HasFactory;

    protected $primaryKey = "SaveID";

    public function Post(){
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

            $safesPost = SavePost::
            where("UserID",$userID)
            ->with([
                "Post"=>function($queryPost) use ($userID){
                    $queryPost->with([
                        "MultimediaPost",
                        "User"=>function($queryUser){
                            $queryUser
                            ->select("UserID","PersonalDataID")
                            ->with([
                                "PersonalData"=>function($queryPersonal){
                                    $queryPersonal->select("PersonalDataID","Nickname");
                                },
                                "Profile"=>function($queryProfile){
                                    $queryProfile->select("ProfileID","ProfilePhotoURL","ProfilePhotoName");
                                }
                            ]);
                        },
                        "Likes"=>function($queryLike) use ($userID){
                            $queryLike->where("NicknameID",$userID);
                        },
                        "Visualizations"=>function($queryVisualization) use ($userID){
                            $queryVisualization->where("NicknameID",$userID);
                        },
                        "Comments"=>function($queryComments) use ($userID){
                            $queryComments->where("UserID",$userID);
                        }
                    ])
                    ->withCount([
                        "Likes",
                        "Comments",
                        "Visualizations",
                        "Safes"
                    ]);
                }
            ])
            ->orderBy("SaveID","desc")
            ->get();

            //seteamos todos los links para interactuar

            foreach($safesPost as $safePost){
                (new UserPost())->setLinksInteraction($safePost->Post);
            }

            return $safesPost;

        }
        catch(\Exception $e){
            return response()->json(["errors"=>"Ha ocurrido un error al obtener los elementos guardados"],500);
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
            return response()->json(["errors"=>"Ha ocurrido un error al guardar/quitar de guardados el posteo"],500);
        }
    }

    public function sendLinkSave(){

    }
}
