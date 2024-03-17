@extends('../../layouts.plantilla')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<meta name="view-transition" content="same-origin" />
<link rel="stylesheet" href="../../css/mainApp/utils/fonts/ubuntuFont.css">
<link rel="stylesheet" href="../../css/mainApp/utils/error/errorAlert.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilNav.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilLoader.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilPost.css">
<link rel="stylesheet" href="../../css/mainApp/utils/responsive/utilFooter.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilRedireckHome.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilProfile.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilPostsAnswers.css">
<link rel="stylesheet" href="../../css/mainApp/profile/likes.css">

<title>{{$profile->Nickname}} Me gustas</title>
@endsection

@section('content')
    <div class="profile_container">
        <div class="redireck_home_container">
            <a class="redireck_home_icon_container" href="{{route("mainApp")}}">
                <i class="fa-solid fa-arrow-left-long"></i>
            </a>
            <div class="redireck_home_content">
                <h3>{{$profile->Nickname}}</h3>
            </div>
        </div>
        <div class="profile_data_container">
            @if ($profile->user->Profile->CoverPhotoURL)
                <div class="profile_cover_container">
                    <img src={{$profile->user->Profile->CoverPhotoURL}} alt={{$profile->user->Profile->CoverPhotoName}}>
                </div>
            @else
            <div class="profile_cover_container">
                <i class="fa-solid fa-camera"></i>
            </div>
            @endif
            <div class="personal_data_container">
                <div class="profile_picture_and_edit">
                    @if ($profile->user->Profile->ProfilePhotoURL)
                        <div class="profile_picture_container">
                            <img src={{$profile->user->Profile->ProfilePhotoURL}} alt={{$profile->user->Profile->ProfilePhotoName}}>
                        </div>
                    @else
                    <div class="profile_picture_container">
                        <h3>{{strtoupper($profile->Nickname[0])}}</h3>
                    </div>
                    @endif
                    @if ($edit)
                        <a class="edit_container" href={{route("editProfilesShow")}}>
                            <p>Editar perfil</p>
                        </a>
                    @endif
                </div>
                <div class="nickname_and_name_container">
                    <h3>{{$profile->user->Name}}</h3>
                    <h5>{{"@".$profile->Nickname}}</h5>
                </div>
                @if ($profile->user->Profile->Biography)
                <div class="biography_container">
                    <p class="biography_content">{{$profile->user->Profile->Biography}}</p>
                </div>
                @endif
                <div class="init_in_app_container">
                    <i class="fa-regular fa-calendar-days"></i>
                    <p>Se ha unido el </p>
                    <p class="date_init">{{$created}}</p>
                </div>
                <div class="follows_and_followers_container">
                    <div class="follows_container">
                        <p class="count_follows">{{$follows}}</p>
                        <p>Seguidos</p>
                    </div>
                    <div class="followers_container">
                        <p class="count_followers">{{$followers}}</p>
                        <p>Seguidores</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="locations">
            <span class="posts_location"><a href="#">Posts</a></span>
            <span class="respuestas_location"><a href="#">Comentarios</a></span>
            <span class="me_gusta_location">Me gustas</span>
        </div>
        <div class="loader_container">
            <div class="loader"></div>
        </div>
        <div class="posts_container">

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="module" src="../../js/mainApp/profile/likes.js"></script>
@endsection