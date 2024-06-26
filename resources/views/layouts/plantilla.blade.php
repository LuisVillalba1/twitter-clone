<!DOCTYPE html>
<html lang="en">
<head>
    {{-- font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    @yield('head')
</head>
<body>
    <div class="main_container">
        @yield("header")
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
                    <a href={{route("settings")}} title="Configuracion">
                        <div class="nav_icon_link_container">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <div class="nav_link_description_container">
                            <p class="nav_link_description">Configuracion</p>
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
                <div class="nav_icon_container delete_session_container">
                    <div class="nav_icon_link_container">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </div>
                    <form class="nav_link_description_container" id="delete_session" action={{route("deleteSession")}} method="POST">
                        @csrf
                        <p class="nav_link_description">Cerrar sesión</p>
                    </form>
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
<script src="{{ asset('js/mainApp/utils/socket/echo.js') }}"></script>
</html>