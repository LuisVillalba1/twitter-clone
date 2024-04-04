@extends('../../../..layouts.settingAccountPlantilla')

@section('head')
<link rel="stylesheet" href="../../../css/mainApp/setting/account/typesConfig/utils/utilsTypeConfig.css">
<link rel="stylesheet" href="../../../css/mainApp/setting/account/typesConfig/changePassword.css">
<link rel="stylesheet" href="../../../css/mainApp/utils/error/errorAlert.css">
<link rel="stylesheet" href="../../../css/mainApp/setting/account/typesConfig/utils/successAlert.css">
<title>Settings account</title>
@endsection


@section('content_results')
    <div class="content_results">
        <div class="redireck_back_container">
            @if (url()->previous() == url()->current() || url()->previous() == route("errorPage"))
                <a href={{route("mainApp")}}>
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            @else
                <a href={{url()->previous()}}>
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            @endif
            <div class="title_container">
                <h3>Cambiar contraseña</h3>
                <div class="header_user_container">
                    <h5>{{"@".$username}}</h5>
                </div>
            </div>
        </div>
        <div class="result_content_container">
            @if ($edit)
            <form action={{route("changePasswordSetting")}} method="POST" class="form_change_password">
                @csrf
                @method("PUT")
                <div class="current_password_container">
                    <div class="input_container">
                        <div class="label_container">
                            <label for="current_user_password">Contraseña actual</label>
                        </div>
                        <input id="current_user_password" type="password" placeholder="Contraseña actual" name="user_password" autocomplete="current-password">
                    </div>
                    <p id="error_user_password" class="error_form"></p>
                    <div class="forgot_password_container">
                        <a href="" class="forgott_password">¿Olvidaste tu contraseña?</a>
                    </div>
                </div>
                <div class="new_password_container">
                    <div class="input_container">
                        <div class="label_container">
                            <label for="new_password">Nueva contraseña</label>
                        </div>
                        <input id="new_password" type="password" placeholder="Nueva contraseña" name="new_password">
                    </div>
                    <p id="error_new_password" class="error_form"></p>
                    <div class="input_container">
                        <div class="label_container">
                            <label for="new_password_repeat">Confirmar contraseña</label>
                        </div>
                        <input id="new_password_repeat" type="password" placeholder="Confirmar contraseña" name="new_password_repeat">
                    </div>
                    <p id="error_new_password_repeat" class="error_form"></p>
                </div>
            </form>
            <div class="buttom_send_container">
                <div class="button_send">
                    <p>Guardar</p>
                </div>
            </div>
            @else
                <p>no</p>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script type="module" src="../../../js/mainApp/setting/account/typesConfig/changePassword.js"></script>
@endsection