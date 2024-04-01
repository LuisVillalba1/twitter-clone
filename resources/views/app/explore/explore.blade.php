@extends('../layouts.mainPlantilla')

@section('head')
<link rel="stylesheet" href="../../css/mainApp/utils/error/errorAlert.css">
<link rel="stylesheet" href="../../css/mainApp/home.css">
<link rel="stylesheet" href="../../css/mainApp/utils/utilPost.css">
<link rel="stylesheet" href="../../css/mainApp/explore/utils/utilsSearch.css">
<link rel="stylesheet" href="../../css/mainApp/explore/search/searchPosts.css">

<title>Explorar</title>
@endsection

@section('header')
    <div class="owner_logo_container">
        <i class="fa-solid fa-user"></i>
    </div>
    <div class="twitter_logo_container">
        <i class="fa-brands fa-x-twitter"></i>
    </div>
    <div class="config_container">
        <i class="fa-solid fa-gear"></i>
    </div>
@endsection

@section('content')
    <div class="main_content">
        <div class="search_title_container">
            <h3>Explorar</h3>
        </div>
        <div class="search_container">
            <div class="input_container">
                <div class="search_icon_container">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" placeholder="Escriba lo que desea buscar" class="input_search">
            </div>
        </div>
        <div class="search_types_container">
            <div class="results_search">
                <div class="recents_header_container">
                    <h4>Recientes</h4>
                    <form action={{route("deleteSearchs")}} method="POST" class="delete_searchs_form">
                        @csrf
                        @method("DELETE")
                        <p>Borrar todo</p>
                    </form>
                </div>
                <div class="result_search__content">
                    
                </div>
            </div>
        </div>
        <div class="search_content">
    
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="../../js/mainApp/utils/responsive/utilNav.js"></script>
<script src="../../js/mainApp/utils/socket/echo.js"></script>
<script type="module" src="../../js/mainApp/explore/utils/utilsSearch.js"></script>
@endsection