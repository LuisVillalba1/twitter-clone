<!DOCTYPE html>
<html lang="en">
<head>
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
                <img src="https://img.freepik.com/foto-gratis/hombre-feliz-pie-playa_107420-9868.jpg?size=626&ext=jpg&ga=GA1.1.1412446893.1705795200&semt=sph" alt="">
            </div>
            <div class="account_name_nickname">
                <h3>Luis Villalba</h3>
                <h5>@firstmotochorro</h5>
            </div>
            <div class="follows_container">
                <div class="follows">
                    <p class="follows__p">28</p>
                    <p class="follows_cant quantity">Siguiendo</p>
                </div>
                <div class="followers">
                    <p class="followers_p">3</p>
                    <p class="followers_cant quantity">Seguidores</p>
                </div>
            </div>
        </div>
        <ul class="nav_links_container">
            <li class="nav_link_li">
                <a href="#">
                    <div class="nav_link__icon_container">
                    <i class="fa-regular fa-user"></i>
    
                    </div>
                    <div class="nav_link__text_container">    
                        <h3>Perfil</h3>
                    </div>
                </a>
            </li>
            <li class="nav_link_li">
                <a href="#">
                    <div class="nav_link__icon_container">
                    <i class="fa-brands fa-x-twitter"></i>
    
                    </div>
                    <div class="nav_link__text_container">
                        <h3>Premium</h3>
                    </div>
                </a>
            </li>
            <li class="nav_link_li">
                <a href="#">
                    <div class="nav_link__icon_container">
                    <i class="fa-solid fa-sheet-plastic"></i>
    
                    </div>
                    <div class="nav_link__text_container">
                        <h3>Listas</h3>   
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
                    <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="nav_link__text_container">
                        <h3>Comunidades</h3>
                    </div>
                </a>
            </li>
            <li class="nav_link_li">
                <a href="#">
                    <div class="nav_link__icon_container">
                    <i class="fa-solid fa-money-bill"></i>
    
                    </div>
                    <div class="nav_link__text_container">
                        <h3>Monetización</h3>
                    </div>
                </a>
            </li>
            <li class="nav_link_li">
                <a href="#">
                    <div class="nav_link__icon_container">
                    <i class="fa-solid fa-gear"></i>
                    </div>
                    <div class="nav_link__text_container">
                        <h3>Configuracion y privacidad</h3>
                    </div>
                </a>
            </li>
            <li class="nav_link_li">
                <a href="#">
                    <div class="nav_link__icon_container">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </div>
                    <div class="nav_link__text_container">
                        <h3>Cerrar sesión</h3>
                    </div>
                </a>
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
                <div class="nav_owner_logo_container">
                    <div class="logo_icon_container">
                        <a href="#" title="Cuenta">
                            <i class="fa-solid fa-l"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @yield("content")
    </div>
    <footer>
        @yield("footer")
    </footer>
</body>
@yield("scripts")
</html>