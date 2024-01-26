<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultimediaPost extends Model
{
    use HasFactory;

    public function UserPost(){
        return $this->belongsTo(UserPost::class,"PostID");
    }
}
