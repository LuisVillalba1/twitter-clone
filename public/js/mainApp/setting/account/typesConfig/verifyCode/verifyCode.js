
const inputs = $(".input_code");

const firstInput = $("#first_input");


$(firstInput).on("paste", function (e) {
    e.preventDefault();
    
    let value = e.originalEvent.clipboardData.getData("text");
    if(parseInt(value)){
        $.each(inputs, function (indexInArray, valueOfElement) {
            //completamos cada input con su valor correspondiente y hacemos focus en el siguiente input
            $(valueOfElement).val(value[indexInArray]);
             if($(inputs[indexInArray + 1]).length > 0){
                $(inputs[indexInArray + 1]).focus();
             }
        });

    }
});

$.each(inputs, function (indexInArray, valueOfElement) { 
     $(valueOfElement).on("keyup", function (e) {
        //en caso de que la tecla presionada sea un nuevo hacemos que el proximo input este focus
        if(e.key >= 0 && e.key <= 9){
            $(inputs[indexInArray + 1]).focus();
        }
        //si se preciona la tecla de borrado vamos para el input de atras
        else if(e.key == "Backspace"){
            if(indexInArray > 0){
                $(inputs[indexInArray -1]).focus();
            }
        }
        //en caso de que la tecla presionada no sea una letra y no se presionen las teclas para pegar
        //llenamos el espacio  con nada
        else if (e.type === 'keyup' && e.keyCode !== 86 && e.keyCode !=17) {
            $(valueOfElement).val("");
        }
     });
});


$(".send_form_buttom").on("click", function () {
    sendForm();
});

function sendForm(){
    console.log($(".verify_code_form").serialize())
    $.ajax({
        type: "POST",
        url: $(".verify_code_form").attr("action"),
        data: $(".verify_code_form").serialize(),
        dataType: "json",
        success: function (response) {
            window.location.href = response.redirect;
        },
        error : function (e){
            if(e.status == 422){
                return $(".form_error").text("Por favor ingrese el codigo correcto");
            }
            $(".form_error").text(e.responseJSON.errors);
        }
    });
}

