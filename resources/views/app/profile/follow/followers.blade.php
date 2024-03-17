@extends('../../../layouts.plantilla')

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
<link rel="stylesheet" href="../../css/mainApp/profile/follow/followers.css">

<title>{{$nickname}} follows</title>
@endsection

@section('content')
    <div class="follow_container">
        <div class="redireck_home_container">
            <a class="redireck_home_icon_container" href="{{route("mainApp")}}">
                <i class="fa-solid fa-arrow-left-long"></i>
            </a>
            <div class="redireck_home_content">
                <h3>{{"@" . $nickname}}</h3>
            </div>
        </div>
        <div class="header_follow">
            <span class="follow_redirect_container"><a href={{route("showUserFollows",["username"=>$nickname])}} class="follows_redirect">Seguidos</a></span>
            <span class="followers_redirect_container"><a href={{route("showUserFollowers",["username"=>$nickname])}} class="followers_redirect">Seguidores</a></span>
        </div>
        <div class="main_content">

        </div>
    </div>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="../../js/mainApp/profile/follow/followers.js"></script>
@endsection