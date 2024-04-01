@extends('../layouts/logInPlantilla')

@section('head')
<link rel="stylesheet" href="./css/access/initSession.css">
<title>Twitter</title>
@endsection

@section('content')
<div class="session_container">
    <header>
        <div class="exit_container">
            <a href={{$routeMain}} class="redirect_main"><i class="fa-solid fa-xmark"></i></a>
        </div>
        <div class="logo_container">
            <i class="fa-brands fa-x-twitter"></i>
        </div>
    </header>
    <div class="session_init">
        <h2>Inicia sesión en X</h2>
        <div class="session_with">
            <form action="" method="POST">
                @csrf
                <a class="session_google_container link_botton" href="{{route("google-siging")}}">
                    <img src="./imgs/google_svg.png" alt=""><p>iniciar sesión con Google</p>
                </a>
            </form>
        </div>
        <div class="other_option">
            <div class="lane_container">
                <div class="lane"></div>
            </div>
            <p>o</p>
            <div class="lane_container">
                <div class="lane"></div>
            </div>
        </div>
        <form action="{{route("initSession")}}" method="POST" class="form_init_session">
            @csrf
            <div class="input_container">
                <div class="label_container">
                    <label for="init_session_input">Correo electrónico o nombre de usuario</label>
                </div>
                <input id="init_session_input" type="text" placeholder="Correo electrónico o nombre de usuario" name="user" autocomplete="username">
            </div>
            <p class="error_user error_form"></p>
            <div class="input_container">
                <div class="label_container">
                    <label for="init_session_input">Contraseña</label>
                </div>
                <input type="password" placeholder="Contraseña" name="password" autocomplete="current-password">
            </div>
            <p class="server_error error_password error_form"></p>
            <div class="send_form">
                <div class="input_send_container">
                    <p>Iniciar sesion</p>
                </div>
            </div>
        </form>
        <a class="forgot_password_container link_botton" href="{{route("recuperateAccount")}}">
            <p>¿Olvidate tu contraseña?</p>
        </a>
    </div>
</div>
@endsection

@section('scripts')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script type="module" src="./js/access/siging.js"></script>
@endsection