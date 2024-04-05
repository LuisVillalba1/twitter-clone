@extends('../../../..layouts.settingAccountPlantilla')

@section('head')
<link rel="stylesheet" href="../../../css/mainApp/setting/account/typesConfig/utils/utilsTypeConfig.css">
<link rel="stylesheet" href="../../../css/mainApp/setting/account/typesConfig/verifyEmail.css">
<link rel="stylesheet" href="../../../css/mainApp/utils/error/errorAlert.css">
<link rel="stylesheet" href="../../../css/mainApp/setting/account/typesConfig/utils/successAlert.css">
<title>Change password</title>
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
                <h3>Verificar su email</h3>
                <div class="header_user_container">
                    <h5>{{"@".$username}}</h5>
                </div>
            </div>
        </div>
        <div class="result_content_container">
            @if ($verified)
                <div class="account_verified">
                    <h4>Ya has verificado tu email previamente</h4>
                </div>
            @else
                <form class="verify_account_container" method="POST" action={{route("sendEmailVerify")}}>
                    @csrf
                    @method("PUT")
                    <div class="form_content_container">
                        <p>Presione en el boton de abajo para verificar su cuenta.Le enviaremos un email al correo electronico: <b>{{$email}}</b></p>
                    </div>
                    <div class="send_email_container">
                        <div class="send_email_buttom">
                            <p>Enviar correo electronico</p>
                        </div>
                    </div>
                </form>
                @section('scripts')
                    <script type="module" src="../../../js/mainApp/setting/account/typesConfig/verifyEmail.js"></script>
                @endsection
            @endif
        </div>
    </div>
@endsection
