@extends('../../../../..layouts.settingAccountPlantilla')

@section('head')
<link rel="stylesheet" href="../../../css/mainApp/setting/account/typesConfig/utils/utilsTypeConfig.css">
<link rel="stylesheet" href="../../../css/mainApp/utils/error/errorAlert.css">
<link rel="stylesheet" href="../../../css/mainApp/setting/account/typesConfig/utils/successAlert.css">
<link rel="stylesheet" href="../../../css/mainApp/setting/account/typesConfig/verifyCode/verifyCode.css">
<title>Email code</title>
@endsection


@section('content_results')
    <div class="content_results">
        <div class="title_content_container">
            <h3>Codigo de verificacion</h3>
        </div>
        <form action={{route("codeEmailVerify")}} method="POST" class="verify_code_form">
            @csrf
            <h4>Ingrese su codigo de verificacion</h4>
            <div class="inputs_container">
                <div class="input_container">
                    <input type="text" maxlength="1" class="input_code" id="first_input" name="number1">
                </div>
                <div class="input_container">
                    <input type="text" maxlength="1" class="input_code" name="number2">  
                </div>
                <div class="input_container">
                    <input type="text" maxlength="1" class="input_code" name="number3">  
                </div>
                <div class="input_container">
                    <input type="text" maxlength="1" class="input_code" name="number4">  
                </div>
                <div class="input_container">
                    <input type="text" maxlength="1" class="input_code" name="number5">  
                </div>
            </div>
        </form>
        <p class="form_error"></p>
        <div class="send_form_container">
            <div class="send_form_buttom">
                <p>Verificar</p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="../../../js/mainApp/setting/account/typesConfig/verifyCode/verifyCode.js"></script>
@endsection