@extends('../layouts/emailPlantilla')

@section('head')
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