@extends('../../layouts.editProfilePlantilla')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<meta name="view-transition" content="same-origin" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilNav.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilLoader.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilFooter.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilRedireckHome.css">
<link rel="stylesheet" href="../../css/mainApp/setting/settingProfile.css">
<title>{{$username}} edit</title>
@endsection

@section('content')
    <div class="edit_profile_container">
        <div class="edit_cropper_container">
            <div class="header_edit_cropper">
                <div class="close_edit_cropper">
                    <i class="fa-solid fa-arrow-left-long" id="close_edit_photo_cropper"></i>
                </div>
                <h3>Editar contenido multimedia</h3>
                <div class="save_edit_cropper">
                    <p>Aplicar</p>
                </div>
            </div>
            <div class="photo_container_cropper">
                <img class="cropper_photo">
            </div>
        </div>
        <div class="header_edit">
            <a class="redireck_profile" href={{route("showProfile",["username"=>$username])}}>
                <i class="fa-solid fa-arrow-left-long"></i>
            </a>
            <h3>Editar perfil</h3>
            <div class="save_edit">
                <p>Guardar</p>
            </div>
        </div>
        <div class="edit_content_container">
            <input type="file" name="cover_photo" id="input_cover_photo">
            <input type="file" name="profile_photo" id="input_profile_photo">
            <div class="img_cover_photo_edit">
                <img class="img_cover_photo">
                <i class="fa-solid fa-camera" id="icon_camera_edit"></i>
            </div>
            <div class="photo_profile_edit">
                <img class="profile_photo">
                <i class="fa-solid fa-camera"></i>
            </div>
            <div class="personal_data_edit">
                <div class="input_container">
                    <div class="label_container">
                        <label for="name">Nombre</label>
                    </div>
                    <input id="name" class="personal_data_input" type="text" placeholder="Nombre" value={{$name}} name="name" autocomplete="username">
                    <div class="max_length_container">
                        <p class="current_length"></p>
                        <p>/</p>
                        <p class="max_length">20</p>
                    </div>
                </div>
                <p class="Errors_name"></p>
                <div class="input_container">
                    <div class="label_container">
                        <label for="name">Biografia</label>
                    </div>
                    <textarea name="bio" id="biografia" placeholder="Biografia" class="personal_data_input"></textarea>
                    <div class="max_length_container">
                        <p class="current_length"></p>
                        <p>/</p>
                        <p class="max_length">280</p>
                    </div>
                </div>
                <p class="Errors_bio"></p>
            </div>
            <p class="Errors_coverPhoto"></p>
            <p class="Errors_profilePhoto"></p>
            <p class="Errors"></p>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../../js/mainApp/setting/settingProfile.js"></script>
@endsection