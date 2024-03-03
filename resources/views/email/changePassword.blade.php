@extends('../layouts/emailPlantilla')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<title>Code account</title>
<style>
</style>
@endsection

@section('content')
    <div class="change_password">
        <h3>Change password</h3>
        <p>Please enter to this <a href="{{$link}}">link</a> to change your password</p>
        <b>This link expires in 1 hour</b>
    </div>
@endsection