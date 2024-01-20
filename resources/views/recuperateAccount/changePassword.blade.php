@extends('../layouts/plantilla')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="../css/recuperateAccount/changePassword.css">
<title>Change password</title>
@endsection

@section('content')
<div class="recuperate_account_container">
    <h3 class="title_recuperate_account">Ingrese su nueva contrasña</h3>
    <div class="loader_container">
        <div class="loader"></div>
    </div>
    <div class="succefully_response_container">
        <h4></h4>
    </div>
    <form action="{{$link}}" method="POST" class="change_password_form">
        @method("PATCH")
        @csrf
        <div class="input_container">
            <div class="label_container">
                <label for="password">Contraseña</label>
            </div>
            <input id="password" type="password" placeholder="Contraseña" name="password" autocomplete="new-password">
        </div>
        <p class="error_password error_form"></p>
        <div class="input_container">
            <div class="label_container">
                <label for="password_repeat">Repetir contraseña</label>
            </div>
            <input id="password_repeat" type="password" placeholder="Repetir contraseña" name="password_repeat" autocomplete="new-password">
        </div>
        <p class="error_password_repeat error_form"></p>
        <p class="error_server"></p>
    </form>
    <div class="continue_container">
        <div class="continue_botton">
            <h3>Cambiar contraseña</h3>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="../js/recuperateAccount/changePassword.js"></script>
@endsection