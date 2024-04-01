<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function redirectAccountData(){
        return redirect()->route("showViewAccountData");
    }

    public function changePassword(){

    }
}
