import { setOpacityChilds,removeOpacity} from "../../../../utils/error/errorAlert.js";
export function createAlertSucces(title,message,redirectLink,container){
    let successContainer = $("<div></div>");
    $(successContainer).addClass("success_alert_container");

    let successTitle = $("<h3></h3>");
    $(successTitle).addClass("success_alert_title");
    $(successTitle).text(title);

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
