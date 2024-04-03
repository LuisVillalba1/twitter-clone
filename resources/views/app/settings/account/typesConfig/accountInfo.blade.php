@extends('../../../..layouts.settingAccountPlantilla')

@section('head')
<link rel="stylesheet" href="../../../css/mainApp/setting/account/typesConfig/accountInfo.css">
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
                <h3>Informacion personal</h3>
                <div class="header_user_container">
                    <h5>{{"@".$username}}</h5>
                </div>
            </div>
        </div>
        <div class="result_content_container">
            <ul class="result_content_ul">
                <li class="result_content_ul__li">
                    <div class="no_edit_container">
                        <h4>Nombre de usuario</h4>
                        <p>{{$username}}</p>
                    </div>
                </li>
                <li class="result_content_ul__li">
                    <a href="" class="result_content_link">
                        <div class="result_content_link_description">
                            <h4>Nombre</h4>
                            <p>{{$name}}</p>
                        </div>
                        <div class="icon_config_continue_container">
                            <i class="fa-solid fa-greater-than"></i>
                        </div>
                    </a>
                </li>
                <li class="result_content_ul__li">
                    <a href="" class="result_content_link">
                        <div class="result_content_link_description">
                            <h4>Correo electronico</h4>
                            <p>{{$email}}</p>
                        </div>
                        <div class="icon_config_continue_container">
                            <i class="fa-solid fa-greater-than"></i>
                        </div>
                    </a>
                </li>
                <li class="result_content_ul__li">
                    <a href="" class="result_content_link">
                        <div class="result_content_link_description">
                            <h4>Fecha de nacimiento</h4>
                            <p>{{$date}}</p>
                        </div>
                        <div class="icon_config_continue_container">
                            <i class="fa-solid fa-greater-than"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="../../../js/mainApp/setting/account/account.js"></script>
@endsection