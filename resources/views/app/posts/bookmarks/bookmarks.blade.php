@extends("../../../layouts.plantilla")

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<meta name="view-transition" content="same-origin" />
<link rel="stylesheet" href="../../css/mainApp/utils/fonts/ubuntuFont.css">
<link rel="stylesheet" href="../../css/mainApp/utils/error/errorAlert.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilLoader.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilNav.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilRedireckHome.css">
<link rel="stylesheet" href="../../css/mainApp/utils/responsive/utilFooter.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilPost.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilSave.css">
<link rel="stylesheet" href="../../css/mainApp/posts/bookmarks/bookmarks.css">


<title>Elementos guardados</title>
@endsection

@section('content')
    <div class="saves_posts_container">
        <div class="redireck_home_container">
            @if (url()->previous() == url()->current())
                <a class="redireck_home_icon_container" href="{{route("mainApp")}}">
                    <i class="fa-solid fa-arrow-left-long"></i>
                </a>
            @else
            <a class="redireck_home_icon_container" href={{url()->previous()}}>
                <i class="fa-solid fa-arrow-left-long"></i>
            </a> 
            @endif
            <div class="redireck_home_content">
                <h3>Elementos Guardados</h3>
            </div>
        </div>
        <div class="loader_container">
            <div class="loader"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="module" src="../../js/mainApp/posts/bookmarks/bookmarks.js"></script>
@endsection