import { createAlertSucces } from "../utils/alertSuccess.js";
import { createErrorAlert } from "../../../../utils/error/errorAlert.js";
const mainContainer = $(".main_container");
const formDate = $(".form_date");

$(".save_buttom").on("click", function () {
    sendForm();
});

//enviamos el formulario
function sendForm(){
    $.ajax({
        type: "PUT",
        url: $(formDate).attr("action"),
        data: $(formDate).serialize(),
        dataType: "json",
        success: function (response) {
            createAlertSucces("Se ha modificado la fecha de nacimiento",response.message,response.redirect,mainContainer)
        },
        error : function (e){
            if(e.status == 422){
                const data = e.responseJSON.errors.date[0];
                return $(".error_date").text(data);
            }
            createErrorAlert(e.responseJSON.errors,mainContainer);

        }
    });
}