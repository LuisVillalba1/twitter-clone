@extends('../../layouts.mainPlantilla')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<meta name="view-transition" content="same-origin" />
<link rel="stylesheet" href="../css/mainApp/posts/createPost.css">

<title>Create post</title>
@endsection

@section('content')
    <div class="main_container">
        <div class="new_post_container">
            <div class="redireck_home_container">
                <a href="{{route("mainApp")}}">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
            <form class="new_post" method="POST" action="{{route("createPost")}}">
                @csrf
                <div class="new_post_content_container">
                    <div class="owner_logo_container">
                        <div class="owner_logo">
                            <h4>L</h4>
                        </div>
                    </div>
                    <div class="new_post_content">
                        <div class="textarea_container">
                            <textarea id="textarea_post" name="message" placeholder="¿Que estas pensando?" autocomplete="off"></textarea>
                        </div> 
                        <div class="error_files_container">
                        </div> 
                        <div class="imgs_container"></div>
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
<script src="../js/mainApp/posts/createPost.js"></script>
<script src="../js/mainApp/homeDragAndDrop.js"></script>
@endsection