@extends("../../../layouts.plantilla")

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<meta name="view-transition" content="same-origin" />
<link rel="stylesheet" href="../../css/mainApp/posts/bookmarks/bookmarks.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilNav.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilRedireckHome.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilFooter.css">


<title>Elementos guardados</title>
@endsection

@section('content')
    <div class="saves_posts_container">
        <div class="redireck_home_container">
            <a class="redireck_home_icon_container" href="{{route("mainApp")}}">
                <i class="fa-solid fa-arrow-left-long"></i>
            </a>
            <div class="redireck_home_content">
                <h3>Elementos Guardados</h3>
            </div>
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

@endsection