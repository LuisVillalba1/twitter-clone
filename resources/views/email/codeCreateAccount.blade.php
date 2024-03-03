
@extends('../layouts/emailPlantilla')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<title>Code account</title>
<style>
    .main_container{
        padding: 0 10px;
    }
    .confirm_account{
        direction: flex;
        flex-direction: column;
    }

    .confirm_account h2{
        font-size: 1.3em;
        font-weight: 200;
    }

    .confirm_account p{
        font-size: 1.2em
    }

    .code_p{
        margin-top: 10px
    }

    #code{
        font-size: 1.4em;
        color: black;
        margin-bottom: 5px
    }

    b{
        font-size: 1.2em
    }

</style>
@endsection

@section('content')
    <div class="confirm_account">
        <h2>Confirma tu direccion de correo</h2>
        <p>Debes de completaar este paso rapido para poder crear tu cuenta.Confirma que esta es la direccion correcta</p>

        <p class="code_p">Introduce este codigo de verificacion para poder usar tu cuenta</p>
        <h3 id="code">{{$code}}</h3>
        <b>Los codigos de verificacion caducan despues de una hora</b>
    </div>
@endsection