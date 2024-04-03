const inputs = $("input");


//recorremos todos los inputs
$.each(inputs, function (indexInArray, valueOfElement) { 
    if(indexInArray != 0){
        $(valueOfElement).on("focus",function(e){
            //obtenemos el contenedor padre y su respectivo label container
            let padre = e.target.closest(".input_container");

            let label = padre.firstElementChild;

            //mostramos el label correspondiente y le agregamos estilos al contenedor
            if(!document.startViewTransition){

                $(e.target).addClass("input_focus");

                showLabel(label);
                $(padre).css("border-color", "rgb(29, 155, 240)");

                return ;
            }
            document.startViewTransition(()=>{
                $(e.target).addClass("input_focus");

                showLabel(label);
                $(padre).css("border-color", "rgb(29, 155, 240)");
            })
            
        });
        //ocultamos el label y le quitamos los estilos al contenedor
        $(valueOfElement).on("blur",function(e){
            let padre = e.target.closest(".input_container");
            let label = padre.firstElementChild;

            $(e.target).removeClass("input_focus");
            showLabel(label);
            $(padre).css("border-color", "rgb(156, 152, 152)");
        })
    }
});

//mostramos u ocultamos los label
function showLabel(label){
    if($(label).attr("class") == "label_container"){
        $(label).removeClass("label_container");
        $(label).addClass("label_container_show");
        let hijo = label.firstElementChild;
        $(hijo).addClass("label_show");
    }
    else{
        $(label).removeClass("label_container_show");
        $(label).addClass("label_container");
    }
}

const inputPassword = $("#init_session_input");
const formPassword = $(".form_password");

//enviamos el formulario en caso de que se presiones la tecla enter en el input o si se presiona el boton de confirmar
$(inputPassword).on("keydown", function (e) {
    if(e.key == "Enter"){
        sendForm();
    }
});

$(".button_send_form").on("click",function(e){
    sendForm();
});

//prevenimos el comportamiento por defecto del formulario
$(formPassword).on("submit ", function (e) {
    e.preventDefault();
});


//enviamos el formulario
function sendForm(){
    let dataForm = $(formPassword).serialize();
    $.ajax({
        type: "POST",
        url: $(formPassword).attr("action"),
        data: dataForm,
        dataType: "json",
        success: function (response) {
            window.location.href = response.url;
        },
        error : function(e){
            $(".error_form").text(e.responseJSON.message);
        }
    });
}