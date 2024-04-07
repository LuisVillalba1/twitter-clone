
@extends('../layouts/emailPlantilla')

@section('head')
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
        <p>Debe de completar este paso para poder verificar tu correo electronico</p>

        <p class="code_p">Introduce este codigo de verificacion</p>
        <h3 id="code">{{$code}}</h3>
        <b>Los codigos de verificacion caducan despues de una hora</b>
    </div>
@endsection