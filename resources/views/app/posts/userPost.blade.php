@extends('../../layouts.mainPlantilla')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<meta name="view-transition" content="same-origin" />
<link rel="stylesheet" href="../../css/mainApp/posts/userPost.css">

<title></title>
@endsection

@section('content')
    <div class="main_container">
        <div class="redireck_home_container">
            <a class="redireck_home_icon_container" href="{{route("mainApp")}}">
                <i class="fa-solid fa-arrow-left-long"></i>
            </a>
            <div class="redireck_home_content">
                <h3>Post</h3>
            </div>
        </div>
        <div class="loader_container">
            <div class="loader"></div>
        </div>
        <div class="post_container">
            <div class="user_data_container">
                <div class="owner_logo_container">
                    <div class="owner_logo">
                        <h4 class="logo"></h4>
                    </div>
                </div>
                <div class="user_nickname_container">
                    <p class="user_nickname"></p>
                </div>
            </div>
            <div class="user_message_container">
                <p class="user_message"></p>
            </div>
            <div class="user_multimedia_container">

            </div>
            <div class="interaction_post_container">
            </div>
            <div class="comments_container">

            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="../../js/mainApp/posts/userPost.js"></script>
@endsection