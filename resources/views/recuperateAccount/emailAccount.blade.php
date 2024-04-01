@extends('../layouts/recuperateAccountPlantilla')

@section('head')
<link rel="stylesheet" href="./css/recuperateAccount/recuperate.css">
<title>RecuperateAccount</title>
@endsection

@section('content')
<div class="recuperate_account_container">
    <h3 class="title_recuperate_account">Ingrese su Email correspondiente</h3>
    <div class="loader_container">
        <div class="loader">
        
        </div>
    </div>
    <div class="succefully_response_container">
        <h4></h4>
    </div>
    <form action="{{route("RecuperateAccountPost")}}" method="POST" class="recuperate_account_form">
        @csrf
        <div class="input_container">
            <div class="label_container">
                <label for="mail">Email</label>
            </div>
            <input id="mail" type="email" placeholder="Email" name="mail" autocomplete="email">
        </div>
        <p class="error_mail error_form"></p>
        <p class="error_server"></p>
    </form>
    <div class="continue_container">
        <div class="continue_botton">
            <h3>Continuar</h3>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="./js/recuperateAccount/recuperate.js"></script>
@endsection