<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPost extends Model
{
    use HasFactory;

    protected $primaryKey = "PostID";

    public function MultimediaPost(){
        return $this->hasMany(MultimediaPost::class,"PostID");
    }

    public function User(){
        return $this->belongsTo(User::class,"UserID");
    }

    public function createPost($user,$message){
        try{
            $userID = $user->UserID;

            $newPost = new UserPost();
            $newPost->Message = $message;
            $newPost->UserID = $userID;
    
            $newPost->save();
    
            return $newPost->PostID;
        }
        catch(\Exception $e){
            return response()->json(["error",$e->getMessage()],500);
        }
    }

    public function getAllPublics(){
        //obtenemos el post junto al nombre del usuario, y su contenido multimedia
        $posts = UserPost::with([
            "MultimediaPost"=>function($query){
                $query->select("PostID","Name","Url");
            },
            "User"=>function($query2){
                $query2->select("UserID","Name","PersonalDataID")
                ->with([
                    "PersonalData"=>function($query3){
                        $query3->select("PersonalDataID","Nickname");
                    }
                ]);
            }
        ])->select("Message","PostID","UserID")->get();

        return $posts;
    }
}
