@extends("../layouts/plantilla")

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<meta name="view-transition" content="same-origin" />
<link rel="stylesheet" href="../css/singup/singup2step.css">
<title>Register</title>
@endsection

@section('content')
    <div class="register_container">
        <div class="header_container">
            <div class="exit_container">
                <a href={{route("main")}} class="redirect_main"><i class="fa-solid fa-xmark"></i></a>
            </div>
            <h4>Paso 2 de 3</h4>
        </div>
        <h3 class="account_title">Datos de la cuenta</h3>
        <form class="register_data" method="POST" action="{{route("singup2create")}}">
            @csrf
            <div class="user_data">
                <div class="input_container">
                    <div class="label_container">
                        <label for="name_input">Nombre de usuario</label>
                    </div>
                    <input name="nickname" type="text" placeholder="Nombe de usuario" id="name_input" autocomplete="username">
                </div>
            </div>
            <p class="error_nickname error_form">

            </p>
            <div class="user_data">
                <div class="input_container">
                    <div class="label_container">
                        <label for="password_input">Contraseña</label>
                    </div>
                    <input name="password" type="password" placeholder="Contraseña" id="password_input" autocomplete="new-password">
                </div>
            </div>
            <p class="error_password error_form">

            </p>

            <p class="username_information">Ingrese un nombre de usuario y contraseña, ten en cuenta que el nombre de usuario luego no lo va poder cambiar</p>
        </form>
        <div class="continue_container">
            <div class="continue_botton">
                <h3>Siguiente</h3>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../js/singup/singup2step.js"></script>
@endsection