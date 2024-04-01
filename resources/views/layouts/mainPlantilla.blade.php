<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
    <meta name="view-transition" content="same-origin" />
    {{-- font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href={{asset("css/mainApp/utils/fonts/ubuntuFont.css")}}>
    <link rel="stylesheet" href={{asset("css/mainApp/utils/responsive/utilFooter.css")}}>
    <link rel="stylesheet" href={{asset("css/mainApp/utils/responsive/utilHeader.css")}}>
    <link rel="stylesheet" href={{asset("css/mainApp/utils/responsive/utilNav.css")}}>
    <link rel="stylesheet" href={{asset("css/mainApp/utils/utilNav.css")}}>
    <link rel="stylesheet" href={{asset("css/mainApp/utils/utilLoader.css")}}>
    @yield("head")
</head>
<body>
    <header>
        @yield("header")
    </header>
    {{-- nav reponsive --}}
    <div class="nav_responsive">
        <div class="border_rigth_blur"></div>
        <div class="account_information">
            <div class="account__logo_container">
                @if ($profilePhoto[0])
                    <img src={{$profilePhoto[0]}} alt={{$profilePhoto[1]}}>
                @else
                    <div class="account_logo">
                        <h3>{{ucfirst($nickname[0])}}</h3>
                    </div>
                @endif
            </div>
            <div class="account_name_nickname">
                <h3>{{$name}}</h3>
                <h5>{{"@".$nickname}}</h5>
            </div>
            <div class="follows_container">
                <div class="follows">
                    <p class="follows__p">{{$follows}}</p>
                    <p class="follows_cant quantity">Siguiendo</p>
                </div>
                <div class="followers">
                    <p class="followers_p">{{$followers}}</p>
                    <p class="followers_cant quantity">Seguidores</p>
                </div>
            </div>
        </div>
        <ul class="nav_links_container">
            <li class="nav_link_li">
                <a href={{route("showProfile",["username"=>$nickname])}}>
                    <div class="nav_link__icon_container">
                    <i class="fa-regular fa-user"></i>
    
                    </div>
                    <div class="nav_link__text_container">    
                        <h3>Perfil</h3>
                    </div>
                </a>
            </li>
            <li class="nav_link_li">
                <a href={{route("showBookmarks")}}>
                    <div class="nav_link__icon_container">
                    <i class="fa-regular fa-bookmark"></i>
    
                    </div>
                    <div class="nav_link__text_container">
                        <h3>Guardados</h3>
                    </div>
                </a>
            </li>
            <li class="nav_link_li">
                <a href="#">
                    <div class="nav_link__icon_container">
                    <i class="fa-solid fa-gear"></i>
                    </div>
                    <div class="nav_link__text_container">
                        <h3>Configuracion</h3>
                    </div>
                </a>
            </li>
            <li class="nav_link_li">
                <div class="delete_session_container">
                    <div class="nav_link__icon_container">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </div>
                    <form class="nav_link__text_container" id="delete_session_form" action={{route("deleteSession")}} method="POST">
                        @csrf
                        <h3>Cerrar sesión</h3>
                    </form>
                </div>
            </li>
        </ul>
    </div>
    @yield("createPost")
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
                    <a href={{route("showExplore")}} title="Buscar">
                        <div class="nav_icon_link_container">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <div class="nav_link_description_container">
                            <p class="nav_link_description">Explorar</p>
                        </div>
                    </a>
                </div>
                <div class="nav_icon_container">
                    <a href={{route("notificationView")}} title="Notificaciones">
                        <div class="nav_icon_link_container notification_container">
                            <i class="fa-regular fa-bell"></i>
                            <div class="count_notification_container">
                                <p class="count_notification"></p>
                            </div>
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
                    <a href={{route("showBookmarks")}} title="Guardados">
                        <div class="nav_icon_link_container">
                            <i class="fa-regular fa-bookmark"></i>
                        </div>
                        <div class="nav_link_description_container">
                            <p class="nav_link_description">Guardados</p>
                        </div>
                    </a>
                </div>
                <div class="nav_owner_icon_container">
                    <a href={{route("showProfile",["username"=>Auth::user()->PersonalData->Nickname])}} title="Cuenta">
                        <i class="fa-solid fa-user"></i>
                    </a>
                </div>
                <div class="nav_owner_logo_container">
                    <a href={{route("showProfile",["username"=>Auth::user()->PersonalData->Nickname])}} class="logo_icon_container" title="Cuenta">
                        Perfil
                    </a>
                </div>
            </div>
        </div>
        @yield("content")
    </div>
    <footer>
        <a href={{route("mainApp")}}><i class="fa-solid fa-house"></i></a>
        <a href={{route("showExplore")}}><i class="fa-solid fa-magnifying-glass"></i></a>
        <a href={{route("notificationView")}} class="notification_container">
            <i class="fa-regular fa-bell"></i>
            <div class="count_notification_container count_notification_container_footer">
                <p class="count_notification"></p>
            </div>
        </a>
        <i class="fa-solid fa-envelope"></i>
    </footer>
</body>
<script>
    {!! Vite::content('resources/js/app.js') !!}
</script>
@yield("scripts")
<script src={{ asset('js/mainApp/closeSession/deleteSession.js') }}></script>
</html>