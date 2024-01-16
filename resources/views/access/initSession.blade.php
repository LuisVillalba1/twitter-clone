@extends('../layouts/plantilla')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<meta name="view-transition" content="same-origin" />
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
                <a class="session_google_container link_botton" href="#">
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
        <form action="" method="POST" class="form_init_session">
            <div class="input_container" id="input_container">
                <div class="label_container">
                    <label for="init_session_input">Teléfono,correo electrónico o nombre de usuario</label>
                </div>
                <input id="init_session_input" type="text" placeholder="Teléfono,correo electrónico o nombre de usuario">
            </div>
            <div class="send_form">
                <div class="input_send_container">
                    <p>Siguiente</p>
                </div>
            </div>
        </form>
        <a class="forgot_password_container link_botton" href="#">
            <p>¿Olvidate tu contraseña?</p>
        </a>
    </div>
</div>
@endsection

@section('scripts')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script type="module" src="./js/access/siging.js"></script>
@endsection