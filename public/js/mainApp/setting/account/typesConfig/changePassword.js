import { createErrorAlert} from "../../../utils/error/errorAlert.js";
import { createAlertSucces } from "./utils/alertSuccess.js";
const inputs = $("input");
const mainContainer = $(".main_container");

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

const form = $(".form_change_password");

$(form).on("sumit", function (e) {
    e.preventDefault();
});

$(".button_send").on("click", function (e) {
    sendForm();
});


//enviamos el formulario
function sendForm(){
    const data = $(form).serialize();

    $.ajax({
        type: "PUT",
        url: $(form).attr("action"),
        data: data,
        dataType: "json",
        success: function (response) {
            createAlertSucces("Se ha cambiado la contraseña",response.message,response.redirect,mainContainer);
        },
        error : function (e){
            if(e.status == 422){
                let errors = e.responseJSON.errors;
                for(let i in errors){
                    $(`#error_${i}`).text(errors[i]);
                }
                return
            }
            createErrorAlert(e.responseJSON.error,mainContainer);
        }
    });
}

const generatePasswordButton = $(".button_generate_password");

function sendMail(){
    
}