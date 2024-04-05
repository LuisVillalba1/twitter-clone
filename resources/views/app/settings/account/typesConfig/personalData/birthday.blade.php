@extends('../../../../..layouts.settingAccountPlantilla')

@section('head')
<link rel="stylesheet" href="../../../css/mainApp/setting/account/typesConfig/utils/utilsTypeConfig.css">
<link rel="stylesheet" href="../../../css/mainApp/setting/account/typesConfig/personalData/birthday.css">
<link rel="stylesheet" href="../../../css/mainApp/utils/error/errorAlert.css">
<link rel="stylesheet" href="../../../css/mainApp/setting/account/typesConfig/utils/successAlert.css">
<title>Change birthday</title>
@endsection


@section('content_results')
    <div class="content_results">
        <div class="title_content_container">
            <h3>Cambiar fecha de nacimiento</h3>
        </div>
        <div class="content_config_container">
            <p>Este valor es la fecha de nacimiento de la persona que utiliza la cuenta, ya sea que quieras crear una cuenta para tu empresa, tu evento o tu gato.</p>
        </div>
        <form action={{route("changeBirthday")}} method="POST" class="form_date">
            @csrf
            @method("PUT")
            <div class="date_container">
                <input type="date" name="date" class="date_input" id="date_input">
            </div>
            <p class="error_date error_form"></p>
            <div class="save_content_container">
                <div class="save_buttom">
                    <p>Guardar</p>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script type="module" src="../../../js/mainApp/setting/account/typesConfig/personalData/birthday.js"></script>
@endsection