
import * as utilsFollowNotification from "./follow/followNotification.js";
import * as utilsPostNotification from "./posts/postsNotification.js";

//contenedor de todas las notificaciones
const notificationsContainer = $(".notifications")

//obtenemos las notificaciones
function getNotifications(){
    $.ajax({
        type: "get",
        url: window.location.href + "/details",
        success: function (response) {
            console.log(response)
            showNotifications(response)
        },
        error : function(error){
            console.log(error)
        }
    });
}

function showNotifications(data){
    for(let i of data){
        let newNotification = $("<a></a>");
        $(newNotification).addClass("notification");

        $(newNotification).attr("href", i.Link);

        //contenedor que contendra ya sea la foto del usuario o el heart
        let containerType = $("<div></div>");
        $(containerType).addClass("action_container");

        //contenedor que contendra el contenido de la notificacion
        let mainNotificationContainer = $("<div></div>");
        $(mainNotificationContainer).addClass("notification_content_container");

        if(i.type == "setting"){
            return
        }

        checkTypeNotification(containerType,mainNotificationContainer,i);

        $(newNotification).append(containerType);
        $(newNotification).append(mainNotificationContainer);
        $(notificationsContainer).append(newNotification);
    }
}

//verificamos que tipo de notificacion es y mostramos el contenido correspondiente
function checkTypeNotification(containerType,ContentContainer,data){
    //obtenemos el nombre de usuario y las foto de este mismo
    let username = data.action_user.Nickname;
    let profilePhoto = data.action_user.user.profile.ProfilePhotoURL;
    let profilePhotoName = data.action_user.user.profile.profilePhotoName;

    //tipo de notificacion y mensaje de la notificacion
    let type = data.Type;
    let message = data.Content;

    if(type == "follow"){
        return followNotification(containerType,ContentContainer,profilePhoto,profilePhotoName,username,message);
    }
    if(type == "Comment"){
        return commentNotification(containerType,ContentContainer,profilePhoto,profilePhotoName,username,message,data.post);
    }
    if(type == "Like"){
        return likeNotification(containerType,ContentContainer,profilePhoto,profilePhotoName,username,message,data.post);
    }
}


//mostramos el contenido para la notificacion de follow
function followNotification(containerType,ContentContainer,photo,photoName,nickname,message){
    //mostramos la imagen y el contenido de la notificacion
    utilsFollowNotification.showPhotoUser(photo,photoName,nickname,containerType);
    $(ContentContainer).append(utilsFollowNotification.showContent(`${nickname} ${message}`,`${nickname}`));
}

//mostramos la notificacion de comentario
function commentNotification(containerType,ContentContainer,photo,photoName,nickname,message,post){
    utilsPostNotification.commentNotification(photo,photoName,nickname,containerType)
    $(ContentContainer).append(utilsPostNotification.showContent(message,nickname,post))
}

//mostramos la notificacion de like
function likeNotification(containerType,ContentContainer,photo,photoName,nickname,message,post){
    utilsPostNotification.likeNotification(photo,photoName,nickname,containerType,ContentContainer)
    $(ContentContainer).append(utilsPostNotification.showContent(message,nickname,post))
}

//eliminamos la cantidadad de notificaciones
localStorage.removeItem("notificationCount");

getNotifications();


const linkProfileContainer = $(".logo_icon_container");

//obtenemos el nombre del usuario 
function getNickname(){
    let linkProfile = $(linkProfileContainer).attr("href");

    let username = linkProfile.split("/");
    return username[username.length - 1];
}

//canala para recibir cada ves que se obtiene una nueva notificacion
Echo.channel(`notification.${getNickname()}`).
listen("notificationEvent",(e)=>{
    //mostramos la nueva notificacion
    if(e.notification.type && e.notification.type == "follow"){
        console.log(e.notification);
        return utilsFollowNotification.showFollowNotification(e.notification,notificationsContainer);
    }
    utilsPostNotification.showNewNotificationPost(e.notification,notificationsContainer);
})