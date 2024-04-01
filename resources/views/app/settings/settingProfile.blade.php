@extends('../../layouts.editProfilePlantilla')

@section('head')
<meta name="view-transition" content="same-origin" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.css">
<link rel="stylesheet" href="../../css/mainApp/utils/fonts/ubuntuFont.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilNav.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilLoader.css">
<link rel="stylesheet" href="../../css/mainApp/utils/responsive/utilFooter.css">
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
                @if ($profileData->CoverPhotoURL)
                    <img class="img_cover_photo" src={{$profileData->CoverPhotoURL}} alt="coverPhoto" title={{$profileData->CoverPhotoName}}>
                @else
                    <img class="img_cover_photo">
                @endif
                <i class="fa-solid fa-camera" id="icon_camera_edit"></i>
            </div>
            <div class="photo_profile_edit">
                @if ($profileData->ProfilePhotoURL)
                    <img class="profile_photo" src={{$profileData->ProfilePhotoURL}} alt="profilePhoto" title={{$profileData->ProfilePhotoName}}>
                @else
                    <img class="profile_photo">
                @endif
                <i class="fa-solid fa-camera"></i>
            </div>
            <form class="personal_data_edit" method="POST" action={{route("editProfile")}}>
                @method('PUT')
                @csrf
                <div class="input_container">
                    <div class="label_container">
                        <label for="name">Nombre</label>
                    </div>
                    <input id="name" class="personal_data_input" type="text" placeholder="Nombre" value={{$name}} name="name" autocomplete="username">
                    <div class="max_length_container">
                        <p class="current_length"></p>
                        <p>/</p>
                        <p class="max_length">15</p>
                    </div>
                </div>
                <p class="errors_name errorsForm"></p>
                <div class="input_container">
                    <div class="label_container">
                        <label for="name">Biografia</label>
                    </div>
                    @if ($profileData->Biography)
                        <textarea name="bio" id="biografia" placeholder="Biografia" class="personal_data_input">{{$profileData->Biography}} </textarea>
                    @else
                        <textarea name="bio" id="biografia" placeholder="Biografia" class="personal_data_input"></textarea>
                    @endif
                    <div class="max_length_container">
                        <p class="current_length"></p>
                        <p>/</p>
                        <p class="max_length">200</p>
                    </div>
                </div>
                <p class="errors_bio errorsForm"></p>
            </form>
            <p class="errors_coverPhoto error_cover errorsForm"></p>
            <p class="errors_profilePhoto error_perfil errorsForm"></p>
            <p class="errors errorsForm"></p>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../../js/mainApp/setting/settingProfile.js"></script>
@endsection