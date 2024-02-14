@extends("../../../layouts.mainPlantilla")

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
<div class="main_container">
    <div class="nav_container">
        <div class="nav">
            <div class="nav_border_filter"></div>
            <div class="nav_icon_container">
                <a href={{route("mainApp")}} title="Home">
                    <i class="fa-brands fa-x-twitter"></i>
                </a>
            </div>
            <div class="nav_icon_container">
                <a href={{route("mainApp")}} title="Home">
                    <div class="nav_icon_link_container">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <div class="nav_link_description_container">
                        <p class="nav_link_description">Home</p>
                    </div>
                </a>
            </div>
            <div class="nav_icon_container">
                <a href="#" title="Buscar">
                    <div class="nav_icon_link_container">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <div class="nav_link_description_container">
                        <p class="nav_link_description">Explorar</p>
                    </div>
                </a>
            </div>
            <div class="nav_icon_container">
                <a href="#" title="Notificaciones">
                    <div class="nav_icon_link_container">
                        <i class="fa-regular fa-bell"></i>
                    </div>
                    <div class="nav_link_description_container">
                        <p class="nav_link_description">Notificaciones</p>
                    </div>
                </a>
            </div>
            <div class="nav_icon_container">
                <a href="#" title="Mensajes">
                    <div class="nav_icon_link_container">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                    <div class="nav_link_description_container">
                        <p class="nav_link_description">Mensajes</p>
                    </div>
                </a>
            </div>
            <div class="nav_icon_container">
                <a href="#" title="Listas">
                    <div class="nav_icon_link_container">
                        <i class="fa-solid fa-sheet-plastic"></i>
                    </div>
                    <div class="nav_link_description_container">
                        <p class="nav_link_description">Listas</p>
                    </div>
                </a>
            </div>
            <div class="nav_icon_container">
                <a href="#" title="Guardados">
                    <div class="nav_icon_link_container">
                        <i class="fa-regular fa-bookmark"></i>
                    </div>
                    <div class="nav_link_description_container">
                        <p class="nav_link_description">Guardados</p>
                    </div>
                </a>
            </div>
            <div class="nav_icon_container">
                <a href="#" title="Amigos">
                  <div class="nav_icon_link_container">
                    <i class="fas fa-user-group"></i>
                  </div>
                  <div class="nav_link_description_container">
                    <p class="nav_link_description">Amigos</p>
                  </div>
                </a>
              </div>
              <div class="nav_icon_container">
                <a href="#" title="Premium">
                  <div class="nav_icon_link_container">
                    <i class="fab fa-twitter"></i>
                  </div>
                  <div class="nav_link_description_container">
                    <p class="nav_link_description">Premium</p>
                  </div>
                </a>
              </div>
            <div class="nav_owner_logo_container">
                <div class="logo_icon_container">
                    <a href="#" title="Cuenta">
                        <i class="fa-solid fa-l"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
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