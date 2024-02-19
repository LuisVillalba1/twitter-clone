@extends('../../layouts.plantilla')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<meta name="view-transition" content="same-origin" />
<link rel="stylesheet" href="../../css/mainApp/utils/utilNav.css">
<link rel="stylesheet" href="../../css/mainApp/posts/userPost.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilComments.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilPost.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilLoader.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilFooter.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilRedireckHome.css">

<title></title>
@endsection


@section('content')
        <div class="post_container">
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
            <div class="current_post">
                <div class="parent_data_container">
                    <div class="parent_user">
                        
                    </div>
                    <a class="parent_post_content">
                        <div class="parent_nickname_container">
                            <p class="parent_nickname"></p>
                        </div>
                        <div class="parent_message_container">
                            <p class="parent_message"></p>
                        </div>
                        <div class="parent_multimedia_container">

                        </div>
                    </a>
                </div>
                <div class="user_data_container">
                    <div class="owner_logo_container">
                        <div class="owner_logo">
                            <h4 class="logo"></h4>
                        </div>
                    </div>
                    <div class="content">
                        <div class="user_nickname_container">
                            <p class="user_nickname"></p>
                        </div>
                        <div class="response_container">
                            <p>En respuesta a </p>
                            <p class="response_post"></p>
                        </div>
                        <div class="user_message_container">
                            <p class="user_message"></p>
                        </div>
                        <div class="user_multimedia_container">
    
                        </div>
                    </div>
                </div>
                <div class="post_information">
                </div>
                <div class="repost_and_likes_container">

                </div>
                <div class="interaction_post_container">
                </div>
            </div>
            <div class="comments_post_container">

            </div>
        </div>
@endsection

@section('footer')
<i class="fa-solid fa-house"></i>
<i class="fa-solid fa-magnifying-glass"></i>
<i class="fa-regular fa-bell"></i>
<i class="fa-solid fa-envelope"></i>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="module" src="../../js/mainApp/posts/userPost.js"></script>
@endsection