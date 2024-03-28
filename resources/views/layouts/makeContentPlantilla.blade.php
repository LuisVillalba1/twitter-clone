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
                    <a href={{route("showBookmarks")}} title="Guardados">
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
</body>
<script>
    {!! Vite::content('resources/js/app.js') !!}
</script>
@yield("scripts")
</html>