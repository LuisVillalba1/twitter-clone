@extends('../layouts.mainPlantilla')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<meta name="view-transition" content="same-origin" />
<link rel="stylesheet" href="./css/mainApp/utils/fonts/ubuntuFont.css">
<link rel="stylesheet" href="./css/mainApp/home.css">
<link rel="stylesheet" href="./css/mainApp/utils/utilNav.css">
<link rel="stylesheet" href="./css/mainApp/utils/utilPost.css">
<link rel="stylesheet" href="./css/mainApp/utils/responsive/utilHeader.css">
<link rel="stylesheet" href="./css/mainApp/utils/responsive/utilNav.css">

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

@section('createPost')
<div class="create_post_container">
    <a href="{{route("showCreatePost")}}">
        <i class="fa-solid fa-feather"></i>
    </a>
</div>
@endsection

@section('content')
        <div class="get_publics" id="{{route("getUsersPosts")}}"></div>
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
        </div>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="module" src="./js/mainApp/home.js"></script>
<script src="./js/mainApp/posts/createPost.js"></script>
<script src="./js/mainApp/utils/responsive/utilNav.js"></script>
<script src="./js/mainApp/homeDragAndDrop.js"></script>
@endsection