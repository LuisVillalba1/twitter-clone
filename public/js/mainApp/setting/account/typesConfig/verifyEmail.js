import {createErrorAlert} from "../../../utils/error/errorAlert.js"
import {createLoader} from "../../../utils/createLoader.js"

const mainContainer = $(".main_container");
const verifyEmailButtom = $(".send_email_buttom");
const formVerifyEmail = $(".verify_account_container");


const resultContainer = $(".result_content_container")

$(verifyEmailButtom).on("click", function () {
    sendForm();
});

$(formVerifyEmail).on("submit", function (e) {
    e.preventDefault();
});

function sendForm(){
    $(formVerifyEmail).remove();
    $(resultContainer).append(createLoader());;
    $.ajax({
        type: "PUT",
        url: $(formVerifyEmail).attr("action"),
        data: $(formVerifyEmail).serialize(),
        dataType: "json",
        success: function (response) {

            let mailSendTitle = $("<h4></h4>");
            $(mailSendTitle).addClass("main_send_title");
            $(mailSendTitle).text(response.message);

            $(resultContainer).append(mailSendTitle);
        },
        error : function (e){
            createErrorAlert(e.responseJSON.errors,mainContainer)
        }
    });

    $(".loader_container").remove();
}