<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\GoogleRegister;
use App\Http\Controllers\InitSession;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecuperateAccountController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\CheckFirstStepRegistration;
use App\Models\Comment;
use App\Models\Like;
use App\Models\SavePost;
use App\Models\UserPost;
use App\Models\Visualization;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//register with google
Route::get('/google-siging', function () {
    return Socialite::driver('google')->redirect();
})->name("google-siging");
 
Route::get('/google-callback', function () {
    $user = Socialite::driver('google')->user();

    return app(App\Http\Controllers\GoogleController::class)->create($user);
});

//Create username with google account
Route::middleware(["GoogleEmail"])->group(function () {
    Route::get("/google/register/username",[GoogleController::class,"showUsernameCreate"])->name("googleUsername");
    Route::post("/googe/register/username",[GoogleController::class,"createUsername"])->name("googleUsernameCreate");
});

//acces controller
Route::controller(AccessController::class)->group(function(){
    Route::get("/","index")->name("main");

    Route::get("/siging","session")->name("siging");
    Route::post("/siging","initSession")->name("initSession");
});

//register controller
Route::controller(RegisterController::class)->group(function(){
    Route::get("/singup","main")->name("singup1step");
    Route::post("/singup","createStep1")->name("singup1create");

    Route::get("/singup/step2","showStep2")->middleware("check_step1_register")->name("singup2step");
    Route::post("/singup/step2","createStep2")->name("singup2create");

    Route::get("/singup/step3","showStep3")->middleware("check_step2_register")->name("singup3step");
    Route::post("/singup/step3","createStep3")->name("singup3create");

    Route::get("/singup/step4","showStep4")->name("singup4step");
});

//recuperateAccount
Route::controller(RecuperateAccountController::class)->group(function(){
    Route::get("/recuperateAccount","recuperateAccount")->name("recuperateAccount");
    Route::post("/recuperateAccount","sendEmail")->name("RecuperateAccountPost");

    Route::get("/recuperateAccount/{id}","changePassword")->name("changePassword");
    Route::patch("/recuperateAccount/{id}","change")->name("changePasswordPatch");
});

Route::get("/error",function(){
    return view("error.errorPage");
})->name("errorPage");

//main app
Route::middleware(["AuthSession"])->group(function () {
    Route::get("/home",[AppController::class,"show"])->name("mainApp");
    //mostramos y permitimos crear un nuevo post
    Route::get("/home/createPost",[AppController::class,"showCreatePost"])->name("showCreatePost");
    Route::post("/home/createPost",[AppController::class,"createPost"])->name("createPost");

    //mostramos todos los posts de los usuarios
    Route::get("/home/getPosts",[AppController::class,"getUsersPosts"])->name("getUsersPosts");

    //likeamos un post
    Route::post("/likePost/{username}/{encryptID}",[Like::class,"likePost"])->name("likePost");

    //realizamos la visualizacion de un post en concreto
    Route::post("/visualization/{username}/{encryptID}",[Visualization::class,"VisualizationPost"])->name("VisualizationPost");

    //mostramos la vista de un post y mostramos la informacion del post
    Route::get("/post/{username}/{encryptID}",[UserPost::class,"showPost"])->name("showPost");
    Route::get("/post/{username}/{encryptID}/details",[UserPost::class,"getPostData"])->name("getPostData");

    Route::get("/comment/{username}/{encryptID}",[Comment::class,"commentPostView"])->name("commentPostView");
    Route::post("/comment/{username}/{encryptID}",[Comment::class,"commentPost"])->name("commentPost");

    Route::get("/bookmarks",[SavePost::class,"showBookmarks"])->name("showBookmarks");
    Route::post("/bookmarks/{username}/{encryptID}",[SavePost::class,"savePost"])->name("savePost");
});