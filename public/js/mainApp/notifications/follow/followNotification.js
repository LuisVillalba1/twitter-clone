
export function showFollowNotification(data,containerNotification){
    let newNotification = $("<a></a>");
    $(newNotification).addClass("notification");

    $(newNotification).attr("href", data.link);

    let username = data.personal_data_follower.Nickname;
    let photoUser = data.personal_data_follower.ProfilePhotoURL;
    let photoUserName = data.personal_data_follower.ProfilePhotoName;

    let containerType = $("<div></div>");
    $(containerType).addClass("action_container");

    let mainNotificationContainer = $("<div></div>");
    $(mainNotificationContainer).addClass("notification_content_container");

    let message = `@${username} ha comensado a seguirte`;

    //mostramos la imagen del usuario en caso de que contenga
    showPhotoUser(photoUser,photoUserName,username,containerType);
    $(mainNotificationContainer).append(showContent(message,username));

    $(newNotification).append(containerType);
    $(newNotification).append(mainNotificationContainer);
    $(containerNotification).prepend(newNotification);
}

//mostramos la imagen del usuario
export function showPhotoUser(photo,photoName,nickname,containerType){
    let imgContainer = $("<div></div>");
    $(imgContainer).addClass("img_action_container");

    if(photo && photoName){
        //mostramos la imagen del usuario
        let img = $("<img></img>");
        $(img).addClass("img_user");
        $(img).attr("src", photo);
        $(img).attr("alt",photoName);

        $(imgContainer).append(img);
        return $(containerType).append(imgContainer);
    }
    let imgUser = $("<div></div>");
    $(imgUser).addClass("img_user");
    let paragraph = $("<p></p>");
    $(paragraph).text(nickname[0].toUpperCase());

    $(imgUser).append(paragraph);
    $(imgContainer).append(imgUser);
    return $(containerType).append(imgContainer);
}

//mostramos el contenido de la notificacion
export function showContent(message,nickname){
    let content = $("<div></div>");
    $(content).addClass("notification_title_container");

    let messageContainer = $("<div></div>");
    $(messageContainer).addClass("notification_title");
    let messageParagrahp = $("<h4></h4>");
    $(messageParagrahp).text(`@${nickname}`);

    $(messageContainer).append(messageParagrahp);

    $(content).append(messageContainer);

    let containerFollowContent = $("<div></div>");

    let messageNotification = $("<p></p>");
    $(messageNotification).text(message);

    $(containerFollowContent).append(messageNotification);

    $(content).append(containerFollowContent);

    return content;
}