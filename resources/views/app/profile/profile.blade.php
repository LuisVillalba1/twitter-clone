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
<link rel="stylesheet" href="../../css/mainApp/profile/follow/utils/alertUnfollow.css">
<link rel="stylesheet" href="../../css/mainApp/profile/profile.css">

<title>{{$profile->Nickname}} profile</title>
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
                    @if ($edit == true)
                        <a class="edit_container" href={{route("editProfilesShow")}}>
                            <p>Editar perfil</p>
                        </a>
                    @else
                        @if($follow)
                        <div class="follow_user_container" id="unfollow">
                            <p>Dejar de seguir</p>
                        </div>
                        @else
                        <div class="follow_user_container" id="follow">
                            <p>Seguir</p>
                        </div>
                        @endif
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
                        <a href={{route("showUserFollows",["username"=>$profile->Nickname])}}>
                            <p class="count_follows">{{$follows}}</p>
                            <p>Seguidos</p>
                        </a>
                    </div>
                    <div class="followers_container">
                        <a href={{route("showUserFollowers",["username"=>$profile->Nickname])}}>
                            <p class="count_followers">{{$followers}}</p>
                            <p>Seguidores</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="locations">
            <span class="posts_location">Posts</span>
            <span class="respuestas_location"><a href="">Respuestas</a></span>
            <span class="me_gusta_location"><a href="#">Me gustas</a></span>
        </div>
        <div class="posts_container">

        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="module" src="../../js/mainApp/profile/profile.js"></script>
@endsection