<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPost extends Model
{
    use HasFactory;

    protected $primaryKey = "PostID";

    public function MultimediaPost(){
        return $this->hasMany(MultimediaPost::class,"MultimediaID","MultimediaID");
    }

    public function user(){
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
}
