@extends('../../layouts.mainPlantilla')

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
        <div class="post_container">
            <div class="loader_container">
                <div class="loader"></div>
            </div>
            <div class="current_post">
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
                        <div class="user_message_container">
                            <p class="user_message"></p>
                        </div>
                        <div class="user_multimedia_container">
    
                        </div>
                    </div>
                </div>
                <div class="interaction_post_container">
                </div>
            </div>
            <div class="comments_post_container">

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="module" src="../../js/mainApp/posts/userPost.js"></script>
@endsection