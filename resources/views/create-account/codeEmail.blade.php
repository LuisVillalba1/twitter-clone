@extends("../layouts/logInPlantilla")

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
<meta name="view-transition" content="same-origin" />
<link rel="stylesheet" href="../css/singup/singup3step.css">
<title>Code account</title>
<style>
    input {
      width: 2em; /* Tamaño de cada cuadrado */
      text-align: center;
      margin: 0 0.2em;
    }
  </style>
@endsection

@section('content')
    <div class="register_container">
        <div class="header_container">
            <div class="exit_container">
                <a href={{route("main")}} class="redirect_main"><i class="fa-solid fa-xmark"></i></a>
            </div>
            <h4>Paso 3 de 3</h4>
        </div>
        <h3 class="account_title">Codigo de verificacion</h3>
        <form class="register_data" method="POST" action="{{route("singup3create")}}">
            <h5 class="verification_title">Ingrese el codigo de verificacion</h5>
            @csrf
            <div class="user_data">                
                <div class="input_container">
                    <input type="text" name="number1" maxlength="1" pattern="\d" class="code-input" id="number1">
                    <input type="text" name="number2" maxlength="1"  pattern="\d" class="code-input" id="number2">
                    <input type="text" name="number3" maxlength="1"  pattern="\d" class="code-input" id="number3">
                    <input type="text" name="number4" maxlength="1"  pattern="\d" class="code-input" id="number4">
                    <input type="text" name="number5" maxlength="1"  pattern="\d" class="code-input" id="number5">
                </div>
                <div class="error_numbers">

                </div>
                
            </div>
            <p class="information">Le hemos enviamos un codigo para poder verificar su cuenta a su correo, por favor ingreselo</p>
        </form>
        <div class="continue_container">
            <div class="continue_botton">
                <h3>Siguiente</h3>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
<script>
//     document.addEventListener('DOMContentLoaded', function() {
//   const codeInputs = document.querySelectorAll('.code-input');

//   codeInputs.forEach((input, index) => {
//     input.addEventListener('input', function() {
//       const value = this.value;

//       // Si el valor es un número y no está vacío
//       if (/^\d$/.test(value)) {
//         // Rellenar el siguiente input si existe
//         const nextIndex = index + 1;
//         if (nextIndex < codeInputs.length) {
//           codeInputs[nextIndex].focus();
//           codeInputs[nextIndex].value = value;
//         }
//       }
//     });

//     // Evitar la entrada manual y saltar al siguiente input al presionar una tecla numérica
//     input.addEventListener('keydown', function(e) {
//       const key = e.key;
//       if (/^\d$/.test(key)) {
//         e.preventDefault();
//         const nextIndex = index + 1;
//         if (nextIndex < codeInputs.length) {
//           codeInputs[nextIndex].focus();
//           codeInputs[nextIndex].value = key;
//         }
//       }
//     });
//   });
// });
</script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../js/singup/singup3.step.js"></script>
@endsection

