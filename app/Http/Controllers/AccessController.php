<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function index(){
        $initSession = route("siging");
        return view("access.main",compact("initSession"));
    }

    public function session(){
        $routeMain = route("main");
        return view("access.initSession",compact("routeMain"));
    }
}
