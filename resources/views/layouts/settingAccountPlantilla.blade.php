<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="view-transition" content="same-origin" />
    <script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
    {{-- font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    {{-- styles --}}
    <link rel="stylesheet" href={{asset("css/mainApp/utils/fonts/ubuntu.css")}}>
    <link rel="stylesheet" href={{asset("css/mainApp/utils/utilNav.css")}}>
    <link rel="stylesheet" href={{asset("css/mainApp/utils/utilLoader.css")}}>
    <link rel="stylesheet" href={{asset("css/mainApp/utils/responsive/utilFooter.css")}}>
    <link rel="stylesheet" href={{asset("css/mainApp/utils/utilsRedireckHome.css")}}>
    <link rel="stylesheet" href={{asset("css/mainApp/setting/account/account.css")}}>
    @yield("head")
</head>
<body>
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
                    <a href={{route("showProfile",["username"=>$username])}} title="Cuenta">
                        <i class="fa-solid fa-user"></i>
                    </a>
                </div>
                <div class="nav_owner_logo_container">
                    <a href={{route("showProfile",["username"=>$username])}} class="logo_icon_container" title="Cuenta">
                        Perfil
                    </a>
                </div>
            </div>
        </div>
        <div class="config_container">
            <div class="header_config">
                <div class="redireck_back_container">
                    @if (url()->previous() == url()->current() || url()->previous() == route("settings") || url()->previous() == route("errorPage"))
                        <a href={{route("mainApp")}}>
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    @else
                        <a href={{url()->previous()}}>
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    @endif
                    <div class="title_container">
                        <h3>Configuracion</h3>
                        <div class="header_user_container">
                            <h5>{{"@".$username}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="types_config_ul">
                <li class="types_config_ul__li" id="account_data_li">
                    <div class="icon_type_config_container">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <a href={{route("showViewAccountData")}} class="link_type_config">
                        <h5>Informacion de la cuenta</h5>
                        <p>Ve la informacion de tu cuenta,como tu nombre y correo electronico</p>
                    </a>
                    <div class="icon_config_continue_container">
                        <i class="fa-solid fa-greater-than"></i>
                    </div>
                </li>
                <li class="types_config_ul__li" id="change_password_li">
                    <div class="icon_type_config_container">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <a href={{route("settingsPassword")}} class="link_type_config">
                        <h5>Cambiar contraseña</h5>
                        <p>Cambia tu contraseña en cualquier momento</p>
                    </a>
                    <div class="icon_config_continue_container">
                        <i class="fa-solid fa-greater-than"></i>
                    </div>
                </li>
                <li class="types_config_ul__li" id="verify_email_li">
                    <div class="icon_type_config_container">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <a href="" class="link_type_config">
                        <h5>Verifica tu cuenta</h5>
                        <p>Verifica tu direccion de correo electronico en caso de no haberlo hecho</p>
                    </a>
                    <div class="icon_config_continue_container">
                        <i class="fa-solid fa-greater-than"></i>
                    </div>
                </li>
            </ul>
        </div>
        @yield("content_results")
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@yield("scripts")
<script src={{ asset('js/mainApp/closeSession/deleteSession.js') }}></script>
</html>