@extends('../../layouts.plantilla')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<meta name="view-transition" content="same-origin" />
<link rel="stylesheet" href="../../css/mainApp/utils/utilNav.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilLoader.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilPost.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilFooter.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilRedireckHome.css">
<link rel="stylesheet" href="../../css/mainApp/profile/profile.css">
<link rel="stylesheet" href="../../css/mainApp/setting/settingProfile.css">

<title>{{$username}} profile</title>
@endsection

@section('content')
    <div class="profile_container">
        <div class="redireck_home_container">
            <a class="redireck_home_icon_container" href="{{route("mainApp")}}">
                <i class="fa-solid fa-arrow-left-long"></i>
            </a>
            <div class="redireck_home_content">
                <h3>{{$username}}</h3>
            </div>
        </div>
        <div class="profile_data_container">
            <div class="profile_cover_container">
                <i class="fa-solid fa-camera"></i>
            </div>
            <div class="personal_data_container">
                <div class="profile_picture_and_edit">
                    <div class="profile_picture_container">
                        <h3>{{strtoupper($username[0])}}</h3>
                    </div>
                    @if ($edit)
                        <a class="edit_container" href={{route("editProfilesShow")}}>
                            <p>Editar perfil</p>
                        </a>
                    @endif
                </div>
                <div class="nickname_and_name_container">
                    <h3>{{$name}}</h3>
                    <h5>{{"@".$username}}</h5>
                </div>
                <div class="init_in_app_container">
                    <i class="fa-regular fa-calendar-days"></i>
                    <p>Se ha unido el </p>
                    <p class="date_init">{{$created}}</p>
                </div>
                <div class="follows_and_followers_container">
                    <div class="follows_container">
                        <p class="count_follows"></p>
                        <p>Seguidos</p>
                    </div>
                    <div class="followers_container">
                        <p class="count_followers"></p>
                        <p>Seguidores</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="locations">
            <span class="posts_location">Posts</span>
            <span class="respuestas_location">Respuestas</span>
            <span class="me_gusta_location">Me gusta</span>
        </div>
        <div class="loader_container">
            <div class="loader"></div>
        </div>
        <div class="posts_container">

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../../js/mainApp/profile/profile.js"></script>
@endsection