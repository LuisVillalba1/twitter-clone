@extends('../layouts/plantilla')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<title>RecuperateAccount</title>
@endsection

@section('content')
<div class="recuperate_account_container">
    <h3>Ingrese su Email correspondiente</h3>
    <form action="{{$link}}" method="POST">
        @csrf
        <div class="input_container">
            <div class="label_container">
                <label for="password"></label>
            </div>
            <input id="password" type="password" placeholder="Contraseña" name="password">
        </div>
        <p class="error_password error_form"></p>
        <div class="input_container">
            <div class="label_container">
                <label for="password_repeat"></label>
            </div>
            <input id="password_repeat" type="password" placeholder="Repetir contraseña" name="password_repeat">
        </div>
        <p class="error_password_repeat error_form"></p>
        <div class="send_form">
            <div class="input_send_container">
                <p>Cambiar contraseña</p>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@endsection