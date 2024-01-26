<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function show(){
        return view("app.main");
    }

    public function showCreatePost(){
        return view("app.posts.createPost");
    }

    public function createPost(Request $request){
        return $request->images;
    }
}

