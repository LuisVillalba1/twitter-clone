
import { showFollowNotification } from "./follow/followNotification.js";
import { showNewNotificationPost } from "./posts/postsNotification.js";
//obtenemos las notificaciones
function getNotifications(){
    $.ajax({
        type: "get",
        url: window.location.href + "/details",
        success: function (response) {
            console.log(response)
            // showNotifications(response)
        },
        error : function(error){
            console.log(error)
        }
    });
}

//eliminamos la cantidadad de notificaciones
localStorage.removeItem("notificationCount");

//contenedor de todas las notificaciones
const notificationsContainer = $(".notifications")

getNotifications();

//por cada notificacion vamos a obtener informacion de comentarios y likes
function showNotifications(data){
    for(let i of data){
        let messageUniq = "";
        let message = "";
        //obtenemos los datos de los comentarios
        if(i.LastUserComment){
            messageUniq = "ha comentado tu posteo";
            message = "han comentado tu posteo";
            showNotification(i,i.LastUserComment,"Comment",i.SumComments,messageUniq,message)
        }
        //obtenemos los datos de los likes
        if(i.LastUserLike){
            messageUniq = "ha indicado que le gusta tu posteo";
            message = "han indico que le gusta tu posteo";
            showNotification(i,i.LastUserLike,"Likes",i.SumLikes,messageUniq,message);
        }
    }
}

function showNotification(allContent,content,typeContent,countActions,uniqActiontext,actionTex){
    //creamos el contenedor de la notificacion
    let newNotification = $("<a></a>");
    $(newNotification).addClass("notification");
    $(newNotification).attr("href", content.Link);

    //obtenemos el link del posteo y el usuario que reliazo el ultimo like
    let linkPost = content.Link;
    let nickname = content.personal_data.Nickname;

    //le añadimos el link del posteo
    $(newNotification).addClass("href",linkPost);

    //contenedor que contendra la imagen correspondiente,de un corazon en caso de que sea un like,o la del usuario si es un comentario
    let containerAction = $("<div></div>");
    $(containerAction).addClass("action_container");

    //contenedor del contenido de la notificacion
    let mainNotificationContainer = $("<div></div>");
    $(mainNotificationContainer).addClass("notification_content_container");

    //creamos el mensaje de la notificacion esta varia de la cantidad de personas que interactuaron con el posteo
    let message = ` ${uniqActiontext}`;
    if(countActions == 2){
        message = ` y ${countActions - 1} persona mas ${actionTex}`;
    }
    if(countActions > 2){
        message = ` y ${countActions} personas mas ${actionTex}`
    }
    //obtenemos las imagenes del usuario
    let userPhoto = content.profile.ProfilePhotoURL;
    let userPhotoName = content.profile.ProfilePhotoName;

    //creamos un objeto con el contenido del posteo
    let post = {
        Message : allContent.Message,
        multimedia_post : [{
            Url : allContent.Multimedia
        }
        ]
    }

    //en caso de que sea un comentario lo mostramos de la manera correspondiente
    if(typeContent == "Comment"){
        //mostramos la imagen,en caso de que posea, del usuario
        commentNotification(userPhoto,userPhotoName,nickname,containerAction)
        //mostramos el contenido del posteo con el cual se interactuo
        $(mainNotificationContainer).append(showContent(message,nickname,post))
    }
    else{
        //mostramos el corazon junto a la imagen del usuario
        likeNotification(userPhoto,userPhotoName,nickname,containerAction,mainNotificationContainer);
        //mostramos el contenido del posteo
        $(mainNotificationContainer).append(showContent(message,nickname,post));
    }

    newNotification.append(containerAction);
    newNotification.append(mainNotificationContainer);
    notificationsContainer.append(newNotification);
}


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
    console.log(e);
    if(e.notification.type && e.notification.type == "follow"){
        return showFollowNotification(e.notification,notificationsContainer);
    }
    showNewNotificationPost(e.notification,notificationsContainer);
})