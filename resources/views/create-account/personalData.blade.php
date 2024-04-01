@extends("../layouts/logInPlantilla")

@section('head')
<link rel="stylesheet" href="./css/singup/singup1step.css">
<title>Register</title>
@endsection

@section('content')
    <div class="register_container">
        <div class="header_container">
            <div class="exit_container">
                <a href={{route("main")}} class="redirect_main"><i class="fa-solid fa-xmark"></i></a>
            </div>
            <h4>Paso 1 de 3</h4>
        </div>
        <h3 class="create_account_title">Crea tu cuenta</h3>
        <form class="register_data" method="POST" action="{{route("singup1create")}}">
            @csrf
            <div class="contact_data">
                <div class="input_container">
                    <div class="label_container">
                        <label for="name_input">Nombre</label>
                    </div>
                    <input name="name" type="text" placeholder="Nombre" id="name_input" autocomplete="name">
                </div>
                <p class="error_name error_form">

                </p>
                <div class="input_container">
                    <div class="label_container">
                        <label for="email_input">Correo electrónico</label>
                    </div>
                    <input name="email" autocomplete="email" type="email" placeholder="Correo electrónico" id="email_input">
                </div>
                <p class="error_email error_form"></p>
            </div>
            <div class="personal_data">
                <h4>Fecha de nacimiento</h4>
                <p>Esta infomación no será pública.Confirma tu propia edad,incluso si esta cuenta es para una empresa,una mascota u otra cosa.</p>
                <div class="date_container">
                    <input type="date" name="date" class="date_input" id="date_input">
                </div>
                <p class="error_date error_form"></p>
            </div>
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
        <script src="./js/singup/singup1step.js"></script>
@endsection