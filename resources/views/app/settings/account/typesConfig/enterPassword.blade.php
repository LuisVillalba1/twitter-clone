@extends('../../../..layouts.settingAccountPlantilla')

@section('head')
<link rel="stylesheet" href="../../../css/mainApp/setting/account/typesConfig/utils/utilsTypeConfig.css">
<link rel="stylesheet" href="../../../css/mainApp/setting/account/typesConfig/setPassword.css">
<title>Enter your password</title>
@endsection


@section('content_results')
    <div class="content_results">
        <div class="redireck_back_container">
            @if (url()->previous() == url()->current()|| url()->previous() == route("errorPage"))
                <a href={{route("mainApp")}}>
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            @else
                <a href={{url()->previous()}}>
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            @endif
            <div class="title_container">
                <h3>Contraseña</h3>
                <div class="header_user_container">
                    <h5>{{"@".$username}}</h5>
                </div>
            </div>
        </div>
        <div class="result_content_container">
            <div class="information_container">
                <h4>Confirma tu contraseña</h4>
                <p>Intresa tu contraseña para recibir esto</p>
            </div>
            <form action={{route("setPasswordSettings")}} method="POST" class="form_password">
                @csrf
                <div class="input_container">
                    <div class="label_container">
                        <label for="init_session_input">Contraseña</label>
                    </div>
                    <input id="input_set_password" type="text" placeholder="Contraseña" name="user_password" autocomplete="username">
                </div>
                <p class="error_form"></p>
            </form>
            <div class="forgot_password_container">
                <a href="" class="forgott_password">¿Olvidaste tu contraseña?</a>
            </div>
            <div class="send_form_container">
                <div class="button_send_form">
                    <h3>Confirmar</3>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="../../../js/mainApp/setting/account/typesConfig/setPassword.js"></script>
@endsection