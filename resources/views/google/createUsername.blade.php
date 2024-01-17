@extends('../layouts/plantilla')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="../../css/google/createUsername.css">
<title>Code account</title>
@endsection

@section('content')
<div class="register_container">
    <h3 class="account_title">Nombre de usuario</h3>
    <form class="register_data" method="POST" action="">
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
        <p class="username_information">Ingrese un nombre de usuario.Ten en cuenta que este luego no lo vas a poder cambiar</p>
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
<script src="../../js/google/createUsername.js"></script>
@endsection