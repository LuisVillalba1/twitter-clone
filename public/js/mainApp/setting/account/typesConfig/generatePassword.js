import { createErrorAlert } from "../../../utils/error/errorAlert.js";

const mainContainer = $(".main_container");

const generatePasswordButton = $(".button_generate_password");
const formPassword = $(".no_password_container")

function sendMail(){
    $(generatePasswordButton).on("click", function () {
        $.ajax({
            type: "PUT",
            url: $(formPassword).attr("action"),
            data : $(formPassword).serialize(),
            dataType: "json",
            success: function (response) {
                
            },
            error : function (e){
                if(e.status == 401){
                    createErrorAlert(e.responseJSON.errors,mainContainer);
                }
            }
        });
    });
}

sendMail();