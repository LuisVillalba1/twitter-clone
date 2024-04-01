@extends('../layouts/logInPlantilla')

@section('head')
<link rel="stylesheet" href="./css/access/main.css">
<title>Twitter</title>
@endsection

@section('content')
<div class="register_container">
    <div class="logo_container">
        <img src="./imgs/vecteezy_x-twitter-social-media-logo-symbol-design-vector-illustration_26579897.jpg" alt="">
    </div>
    <div class="create_account_content">
            <h2>Lo que esta pasando ahora</h2>
        <h4>Únete Hoy</h4>
        <div class="register_with">
            <a class="session_google_container link_botton" href="{{route("google-siging")}}">
            <img src="./imgs/google_svg.png" alt=""><p class="google_session_p">Registrarse con Google</p>
            </a>
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
        <div class="create_account_container">
            <a class="create_account link_botton" href="{{route("singup1step")}}">
            <p>Crear cuenta</p>
            </a>
        </div>
        <div class="conditions">
            <p>Al registrarte, aceptas los <a href="#">Términos de servicio</a> y la <a href="#">Política de privacidad</a>, incluida la política de <a href="#">Uso de Cookies</a>.</p>
        </div>
        <div class="init_session_container">
            <h4>¿Ya tienes una cuenta?</h4>
            <a class="init_session link_botton" href={{$initSession}}>
                <p>Iniciar sesión</p>
            </a>
        </div>
    </div>
</div>
@endsection

@section('footer')
<footer>
<a href="#">Información</a>
<a href="#">Descarga la app de X</a>
<a href="#">Centro de Ayuda</a>
<a href="#">Condiciones de Servicio</a>
<a href="#">Política de Privacidad</a>
<a href="#">Política de cookies</a>
<a href="#">Accesibilidad</a>
<a href="#">Información de anuncios</a>
<a href="#">Blog</a>
<a href="#">Estado</a>
<a href="#">Empleos</a>
<a href="#">Recursos para marcas</a>
<a href="#">Publicidad</a>
<a href="#">Marketing</a>
<a href="#">X para empresas</a>
<a href="#">Desarrolladores</a>
<a href="#">Guía</a>
<a href="#">Configuración</a>
<a href="#">© 2024 X Corp.</a>
</footer>
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./js/access/main.js"></script>
@endsection