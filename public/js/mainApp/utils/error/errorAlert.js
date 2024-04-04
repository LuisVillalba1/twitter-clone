
export function createErrorAlert(message,container){
    let errorContainer = $("<div></div>");
    $(errorContainer).addClass("error_alert_container");

    let errorTile = $("<h3></h3>");
    $(errorTile).addClass("error_alert__title");
    $(errorTile).text("Error");
    let errorMessageContainer = $("<div></div>");
    $(errorMessageContainer).addClass("error_alert__message_container");

    let messageTex = $("<p></p>");
    $(messageTex).addClass("error_alert__message");
    $(messageTex).text(message);

    $(errorMessageContainer).append(messageTex);

    let continueContainer = $("<div></div>");
    $(continueContainer).addClass("error_alert__continue_container");

    let continueText = $("<p></p>");
    $(continueText).text("OK");

    $(continueContainer).append(continueText);

    $(errorContainer).append(errorTile);
    $(errorContainer).append(errorMessageContainer);
    $(errorContainer).append(continueContainer);

    //añadimos al contenedor el error
    $(container).append(errorContainer);
    //permitimos que la alerta se vea bien
    setOpacityChilds(container,errorContainer);

    //ocultamos la alerta y removemos la opacidad
    closeAlert(container,errorContainer);
}

//cerrramos la alerta
function closeAlert(container,alert){
    let continueContainer = $(alert).children(".error_alert__continue_container");

    $(continueContainer).on("click", function () {
        //cerramos la alerta y removemos la opacidad
        removeOpacity(container,alert)
        $(alert).css("display", "none");
    });
}

//permitimos que la alerta se vea bien, por lo cual por cada hijo del conetenedor le agregamos opacidad
export function setOpacityChilds(container,errorContainer){
    $(container).children().each(function (indexInArray, valueOfElement) { 
        if(valueOfElement != errorContainer[0]){
            $(valueOfElement).css("opacity", "0.2");
        }
    });
}


//removemos la opacidad
export function removeOpacity(container,errorContainer){
    $(container).children().each(function (indexInArray, valueOfElement) { 
        if(valueOfElement != errorContainer[0]){
            $(valueOfElement).css("opacity", "1");
        }
    });
}