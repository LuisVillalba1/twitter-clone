<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    //obtenemos la interaccion correspondiente
    public function Interaction(){
        return $this->belongsTo(PostsInteraction::class,"InteractionID");
    }
}
