@extends("../layouts/logInPlantilla")

@section('head')
<link rel="stylesheet" href="../css/singup/singup2step.css">
<title>Passoword account</title>
@endsection

@section('content')
    <div class="register_container">
        <div class="header_container">
            <div class="exit_container">
                <a href={{route("main")}} class="redirect_main"><i class="fa-solid fa-xmark"></i></a>
            </div>
            <h4>Paso 4 de 4</h4>
        </div>
        <h3 class="account_title">Contraseña de la cuenta</h3>
        <form class="register_data" method="POST" action="{{route("singup2create")}}">
            @csrf
            <div class="user_data">
                <div class="input_container">
                    <div class="label_container">
                        <label for="name_input">Contraseña</label>
                    </div>
                    <input name="password" type="password" placeholder="Nombe de usuario" id="name_input" autocomplete="new-password">
                </div>
            </div>
            <p class="error_password error_form">

            </p>
            <p class="username_information">Le pediremos que ingrese una contraseña para su cuenta</p>
        </form>
        <div class="continue_container">
            <div class="continue_botton">
                <h3>Terminar</h3>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../js/singup/singup2step.js"></script>
@endsection