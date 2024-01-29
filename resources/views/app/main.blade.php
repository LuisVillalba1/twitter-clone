@extends('../layouts.mainPlantilla')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<meta name="view-transition" content="same-origin" />
<link rel="stylesheet" href="./css/mainApp/home.css">

<title>Twitter</title>
@endsection

@section('header')
    <div class="owner_logo_container">
        <i class="fa-solid fa-user"></i>
    </div>
    <div class="twitter_logo_container">
        <i class="fa-brands fa-x-twitter"></i>
    </div>
    <div class="config_container">
        <i class="fa-solid fa-gear"></i>
    </div>
@endsection

@section('content')
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
                <a href="#">
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
    <div class="create_post_container">
        <a href="{{route("showCreatePost")}}">
            <i class="fa-solid fa-feather"></i>
        </a>
    </div>
    <div class="main_container">
        <div class="nav_container">
            <div class="nav">
                <div class="nav_border_filter"></div>
                <div class="nav_icon_container">
                    <a href="#" title="Home">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                </div>
                <div class="nav_icon_container">
                    <a href="#" title="Home">
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
        <div class="posts_container">
            <div class="header">
                <h2>Inicio</h2>
                <div class="header_icon_config_container">
                    <i class="fa-solid fa-gear"></i>
                </div>
            </div>
            <div class="create_post_main">
                <div class="create_post_logo_container">
                    <div class="create_post_logo">
                        <i class="fa-solid fa-l"></i>
                    </div>
                </div>
                <form class="new_post" method="POST" action="{{route("createPost")}}" enctype="multipart/form-data">
                    @csrf
                    <div class="new_post_content_container">
                        <div class="textarea_container">
                            <textarea id="textarea_post" name="message" placeholder="¿Que estas pensando?" autocomplete="off" maxlength="280"></textarea>
                            <div class="textarea_length_container">
                                <p class="current_length_textarea">0</p>
                                <p>/</p>
                                <p class="max_length_textarea">280</p>
                            </div>
                        </div> 
                        <div class="error_files_container">
                        </div> 
                        <div class="imgs_container">
                        </div>
                    </div>
                    <div class="send_and_add_content">
                        <div class="send_and_add_content__icons">
                            <input type="file" id="upload_img_input" multiple>
                            <i class="fa-regular fa-image icon_upload_img"></i>
                        </div>
                        <div class="send_and_add_content__repost">
                            <p>Postear</p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="post">
                <div class="logo_container">
                    <div class="img_container">
                        <img src="https://img.freepik.com/foto-gratis/hombre-feliz-pie-playa_107420-9868.jpg?size=626&ext=jpg&ga=GA1.1.1412446893.1705795200&semt=sph" alt="">
                    </div>
                </div>
                <div class="post_content">
                    <div class="name_container">
                        <h5>BA Tránsito</h5>
                    </div>
                    <div class="content">
                        <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                        Quasi, in veritatis similique quod non placeat corporis incidunt at earum molestiae ut. 
                        Id cupiditate vero dignissimos vel autem, consequuntur sint ad.
                        </p>
                    </div>
                    <div class="interaction">
                        <div class="comment_container">
                            <i class="fa-regular fa-comment interaction_icon"></i>
                        </div>
                        <div class="repost_container">
                            <i class="fa-solid fa-repeat interaction_icon"></i>
                        </div>
                        <div class="like_container">
                            <div class="heart_bg">
                                <div class="heart_icon">

                                </div>
                            </div>
                            <div class="likes_count"></div>
                        </div>
                        <div class="views_container">
                            <i class="fa-solid fa-chart-simple interaction_icon"></i>
                        </div>
                    </div>
                </div>
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
<script src="./js/mainApp/home.js"></script>
<script src="./js/mainApp/posts/createPost.js"></script>
<script src="./js/mainApp/homeDragAndDrop.js"></script>
@endsection