@extends('../../../..layouts.settingAccountPlantilla')

@section('head')
<link rel="stylesheet" href="../../../css/mainApp/setting/account/typesConfig/utils/utilsTypeConfig.css">
<title>Settings account</title>
@endsection


@section('content_results')
    <div class="content_results">
        <div class="redireck_back_container">
            @if (url()->previous() == url()->current() || url()->previous() == route("errorPage"))
                <a href={{route("mainApp")}}>
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            @else
                <a href={{url()->previous()}}>
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            @endif
            <div class="title_container">
                <h3>Cambiar contraseña</h3>
                <div class="header_user_container">
                    <h5>{{"@".$username}}</h5>
                </div>
            </div>
        </div>
        <div class="result_content_container">
            @if ($edit)
                <p>jkasdlg</p>
            @else
                <p>no</p>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script src="../../../js/mainApp/setting/account/account.js"></script>
@endsection