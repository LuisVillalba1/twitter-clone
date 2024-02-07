@extends('../../layouts.mainPlantilla')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<meta name="view-transition" content="same-origin" />
<link rel="stylesheet" href="../../css/mainApp/posts/createPost.css">
<link rel="stylesheet" href="../../css/mainApp/posts/commentPost.css">

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
            <div class="post_to_comment">
                <div class="owner_logo_container post_to_comment_logo">
                    <div class="owner_logo">
                        <h4>L</h4>
                    </div>
                    <div class="lane">

                    </div>
                </div>
                <div class="post_to_comment_content_container">
                    <div class="post_to_comment_nick_container">
                        <h3>{{$post->User->personalData->Nickname}}</h3>
                    </div>
                    <div class="post_to_comment_content">
                        <p>{{$post->Message}}</p>
                    </div>
                    <div class="url_images">
                        @if ($post->MultimediaPost && count($post->MultimediaPost) > 0)
                            @foreach ($post->MultimediaPost as $multimedia)
                                <p>{{$multimedia->Url}}</p>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <form class="new_post" method="POST" action="{{$post->linkComment}}">
                @csrf
                <div class="new_post_content_container">
                    <div class="owner_logo_container">
                        <div class="owner_logo">
                            <h4>L</h4>
                        </div>
                    </div>
                    <div class="new_post_content">
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
                        <div class="errors_form"></div>
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
<script src="../../js/mainApp/homeDragAndDrop.js"></script>
@endsection