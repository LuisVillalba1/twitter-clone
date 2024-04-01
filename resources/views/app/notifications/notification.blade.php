@extends('../../layouts.mainPlantilla')

@section('head')
<link rel="stylesheet" href="../../css/mainApp/notifications/notifications.css">

<title>Notificaciones</title>
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
    <div class="notifications_container">
        <div class="header_notifications">
            <h3>Notificaciones</h3>
        </div>
        <div class="notifications">
            
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../../js/mainApp/utils/responsive/utilNav.js"></script>
    <script type="module" src="../../js/mainApp/notifications/notification.js"></script>
@endsection