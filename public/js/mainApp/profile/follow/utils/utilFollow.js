
import { createErrorAlert,setOpacityChilds,removeOpacity } from "../../../utils/error/errorAlert.js";

const mainContent = $(".main_content");
const followContainer = $(".follow_container")

//creamos los cotenedores de la notificacion y le agregamos el contenido correspondiente
export function createFollow(dataUser){
    let container = $("<div></div>");
    $(container).addClass("follow_user_container");

    //contenedos de la imagen del usuario
    let photoContainer = $("<div></div>");
    $(photoContainer).addClass("photo_user_container");

    //contenedor de la informacion del usuario
    let contentUser = $("<div></div>");
    $(contentUser).addClass("user_content");


    let userPhoto = dataUser.user.profile.ProfilePhotoURL;
    let userPhotoName = dataUser.user.profile.ProfilePhotoName;
    let nickname = dataUser.Nickname;


    appendFollowUser(photoContainer,userPhoto,userPhotoName,nickname);
    showUserInformation(contentUser,dataUser);

    $(container).append(photoContainer);
    $(container).append(contentUser);
    followOrUnfollowUser(container,dataUser.followed,dataUser.linkFollow);

    $(mainContent).append(container);
}

//mostramos la foto del usuario en caso de que la contenga
function appendFollowUser(photoContainer,photo,photoName,nickname){
    let photoUser = $("<div></div>")
    $(photoUser).addClass("photo_img_container");

    if(photo && photoName){
        let img = $("<img></img>");
        $(img).addClass("img_photo_user");

        $(img).attr("src", photo);
        $(img).attr("alt",photoName);

        $(photoUser).append(img);
        return $(photoContainer).append(photoUser);
    }

    let firstLetter = nickname[0].toUpperCase();
    let firstLetterTitle = $("<h3></h3>");
    $(firstLetterTitle).text(firstLetter);

    $(photoUser).append(firstLetterTitle);
    return $(photoContainer).append(photoUser);
}

//mostramos la informacion del usuario
function showUserInformation(containerContent,userData){
    let nameUserTitle = $("<h4></h4>");
    $(nameUserTitle).addClass("user_title");
    $(nameUserTitle).text(userData.user.Name);

    $(containerContent).append(nameUserTitle);

    let nicknameContainer = $("<div></div>");
    $(nicknameContainer).addClass("nickname_user_container");
    let paragraph = $("<p></p>");
    $(paragraph).text(`@${userData.Nickname}`);

    $(nicknameContainer).append(paragraph);
    $(containerContent).append(nicknameContainer);

    if(userData.user.profile.Biography){
        let biographyContainer = $("<div></div>");
        $(biographyContainer).addClass("biography_user_container");
        let biographpyParagraph = $("<p></p>");
        $(biographpyParagraph).addClass("biography_user");
        $(biographpyParagraph).text(userData.user.profile.Biography);

        $(biographyContainer).append(biographpyParagraph);
        return $(containerContent).append(biographyContainer); 
    }

    return containerContent;
}

function followOrUnfollowUser(followContainer,followed,followLink){
    if(!followLink){
        return;
    }
    let followUser= $("<div></div>");
    $(followUser).addClass("follow_or_unfollow_user");
    //contenedor para poder seguirlo o dejarlo de seguir
    let followOrUnfollowForm = $("<form></form>");
    $(followOrUnfollowForm).addClass("follow_form_container");

    //añadimos el link y el metodo para poder seguir o dejar de seguir al usuario
    $(followOrUnfollowForm).attr("action", followLink);
    $(followOrUnfollowForm).attr("method", "POST");
    let textFollow = $("<p></p>");
    $(textFollow).addClass("text_follow");
    //mostramos el mensaje en especifico en caso de que se siga o no al usuario
    if(followed){
        $(textFollow).text("Dejar de seguir");
    }
    else{
        $(textFollow).text("Seguir");
    }

    $(followUser).append(followOrUnfollowForm);
    $(followOrUnfollowForm).append(textFollow);

    $(followContainer).append(followUser);

    //peritimos seguir o dejar de seguir al usuario
    createAllertUnfollow(followOrUnfollowForm)
}

//
function createAllertUnfollow(follow_form){
    $(follow_form).on("click", function (e) {
        //obtenemos el link de follow y el texto del form
        let actionLink = $(this).attr("action");
        let textFollow = $(this).children(".text_follow");
        //obtenemo el nombre de usuario al que se quiere seguir o dejar de seguir
        let dataArray = actionLink.split("/");
        let user = dataArray[dataArray.length - 2];

        //en caso de que se quiera dejar de seguir cremos una alerta de la misma
        if($(textFollow).text() == "Dejar de seguir"){
            return createAlertUnfollow(user,actionLink,textFollow)
        }
        followOrUnfollowUserFetch(actionLink,textFollow);
    });
}

//seguimos o dejamos de seguir al usuario
function followOrUnfollowUserFetch(link,text_follow){
    $.ajax({
        type: "POST",
        url: link,
        success: function (response) {
            $(text_follow).text(response.message);
        },
        error : function(e){
            createErrorAlert(e.responseJSON.errors,followContainer)
        }
    });
}

function createAlertUnfollow(user,linkUnfollow,text_follow){
    //creamos un contenedor para preguntar si en verdad desea dejar de seguir al usuario
    let alertContainer = $("<div></div>");
    $(alertContainer).addClass("unfollow_container_alert");

    //titulo
    let title = $("<h4></h4>");
    $(title).addClass("alert_title");
    $(title).text(`¿Estas seguro que quieres dejar de seguir a ${user}?`);

    //mensage 
    let messageContainer = $("<div></div>");
    $(messageContainer).addClass("unfollow_message_container");
    let messageTex = $("<p></p>");
    $(messageTex).addClass("unfollow_message");
    $(messageTex).text("Podras volver a seguirlo posteriormente si lo deseas");
    $(messageContainer).append(messageTex);

    let responseContainer = $("<div></div>");
    $(responseContainer).addClass("response_unfollow_container");

    //contenedor dejar de seguir
    let unfollowContainer = $("<div></div>");
    $(unfollowContainer).addClass("unfollow_container");
    let textUnfollow = $("<p></p>");
    $(textUnfollow).text("Dejar de seguir");
    $(unfollowContainer).append(textUnfollow);

    //contenedor cancelar accion
    let cancelContainer = $("<div></div>");
    $(cancelContainer).addClass("cancel_container");
    let textCancel = $("<p></p>");
    $(textCancel).text("Cancelar");
    $(cancelContainer).append(textCancel);

    $(responseContainer).append(unfollowContainer);
    $(responseContainer).append(cancelContainer);

    $(alertContainer).append(title);
    $(alertContainer).append(messageContainer);
    $(alertContainer).append(responseContainer);

    //lo añadimos al dom
    $(followContainer).append(alertContainer);

    //perimitimos cancelar la operacion o dejar de seguir al usuario
    setOpacityChilds(mainContent,alertContainer)
    unfollow(unfollowContainer,alertContainer,linkUnfollow,text_follow);
    closeAlertUnfollow(cancelContainer,alertContainer);
}

//dejamos de seguir al usuario
function unfollow(container,alertContainer,unfollowLink,text_follow){
    $(container).on("click", function () {
        removeAlertAndOpactity(alertContainer);
        followOrUnfollowUserFetch(unfollowLink,text_follow)
    });
}

//cerramos la alerta de unfollow en caso de que se desee
function closeAlertUnfollow(cancelContainer,alertContainer){
    $(cancelContainer).on("click", function () {
        removeAlertAndOpactity(alertContainer)
    });
}

//eliminamos la alerta y removemos la opacidad del dom
function removeAlertAndOpactity(alertContainer){
    $(".unfollow_container_alert").remove();
    //removemos la opacidad de los objetos
    removeOpacity(mainContent,alertContainer)
}