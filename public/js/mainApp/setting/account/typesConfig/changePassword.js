import { createErrorAlert,removeOpacity,setOpacityChilds} from "../../../utils/error/errorAlert.js";
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

function createAlertSucces(message,redirectLink,container){
    let successContainer = $("<div></div>");
    $(successContainer).addClass("success_alert_container");

    let successTitle = $("<h3></h3>");
    $(successTitle).addClass("success_alert_title");
    $(successTitle).text("Se ha cambiado la contraseña");

    let messageContainer = $("<div></div>");
    $(messageContainer).addClass("success_alert__message_container");

    let messageTex = $("<p></p>");
    $(messageTex).addClass("success_alert__message");
    $(messageTex).text(message);

    $(messageContainer).append(messageTex);

    let continueContainer = $("<div></div>");
    $(continueContainer).addClass("success_alert__continue_container");

    let continueText = $("<a></a>");
    $(continueText).attr("href", redirectLink);
    $(continueText).text("Continuar");

    $(continueContainer).append(continueText);

    $(successContainer).append(successTitle);
    $(successContainer).append(messageContainer);
    $(successContainer).append(continueContainer);

    //añadimos al contenedor el error
    $(container).append(successContainer);
    //permitimos que la alerta se vea bien
    setOpacityChilds(container,successContainer);

    //ocultamos la alerta y removemos la opacidad
    closeAlert(container,successContainer);
}


//cerrramos la alerta
function closeAlert(container,alert){
    let continueContainer = $(alert).children(".succes_alert__continue_container");

    $(continueContainer).on("click", function () {
        //cerramos la alerta y removemos la opacidad
        removeOpacity(container,alert)
        $(alert).css("display", "none");
    });
}



//enviamos el formulario
function sendForm(){
    const data = $(form).serialize();

    $.ajax({
        type: "PUT",
        url: $(form).attr("action"),
        data: data,
        dataType: "json",
        success: function (response) {
            createAlertSucces(response.message,response.redirect,mainContainer);
        },
        error : function (e){
            if(e.status == 422){
                let errors = e.responseJSON.errors;
                for(let i in errors){
                    console.log(i);
                    $(`#error_${i}`).text(errors[i]);
                }
                return
            }
            createErrorAlert(e.responseJSON.error,mainContainer);
        }
    });
}