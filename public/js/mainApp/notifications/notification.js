
//obtenemos las notificaciones
function getNotifications(){
    $.ajax({
        type: "get",
        url: window.location.href + "/details",
        success: function (response) {
            showNotifications(response)
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

//en caso de que sea un comentario mostramos la notificacion de la forma deseada
function commentNotification(photo,photoName,nickname,containerType){
    $(containerType).append(showImgComment(photo,photoName,nickname));
}

//en el caso de que haya sido un comentario la notificacion mostramos al costado la foto del usuario
function showImgComment(photo,photoName,nickname){
    let imgContainer = $("<div></div>");
    $(imgContainer).addClass("img_action_container");
    //si es un comentario mostramos la foto del usuario
    if(photo && photoName){
        //mostramos la imagen del usuario
        let img = $("<img></img>");
        $(img).addClass("img_user");
        $(img).attr("src", photo);
        $(img).attr("alt",photoName);

        $(imgContainer).append(img);
        return imgContainer;
    }
    //en caso de que el usuario no contenga una imagen mostramos la primera letra de su nickname
    let imgUser = $("<div></div>");
    $(imgUser).addClass("img_user");
    let paragraph = $("<p></p>");
    let firstLetter = nickname[0].toUpperCase();
    $(paragraph).text(firstLetter);

    $(imgUser).append(paragraph);
    $(imgContainer).append(imgUser);

    return imgContainer;
}

//mostramos la notificacion en caso de que la accion haya sido de like
function likeNotification(photo,photoName,nickname,containerType,mainNotificationContainer){
    //agregamos el corazon y la imagen del usuario en caos de que posea
    $(containerType).append(showHeart());
    $(mainNotificationContainer).append(showImgLike(photo,photoName,nickname))
}

function showImgLike(photo,photoName,nickname){
    let imgContainer = $("<div></div>");
    $(imgContainer).addClass("img_like_user");
    if(photo && photoName){
        //mostramos la imagen del usuario
        let img = $("<img></img>");
        $(img).addClass("img_user");
        $(img).attr("src", photo);
        $(img).attr("alt",photoName);

        $(imgContainer).append(img);
        return imgContainer;
    }
    //en caso de que el usuario no contenga una imagen mostramos la primera letra de su nickname
    let imgUser = $("<div></div>");
    $(imgUser).addClass("img_user");
    let paragraph = $("<p></p>");
    let firstLetter = nickname[0];
    $(paragraph).text(nickname[0].toUpperCase());
    $(imgUser).append(paragraph);
    $(imgContainer).append(imgUser);

    return imgContainer;
}

//añadimos un corazon en caso de que haya sido un like
function showHeart(){
    let img = $("<i></i>");
    $(img).addClass("fa-solid fa-heart");
    return img;
}

//mostramos el contenido de la notificacion
function showContent(message,nickname,post){
    let content = $("<div></div>");
    $(content).addClass("notification_content");
    
    let messageContainer = $("<div></div>");
    $(messageContainer).addClass("message_container");
    let messageParagrahp = $("<p></p>");
    $(messageParagrahp).text(`${nickname} ${message}`);

    $(messageContainer).append(messageParagrahp);

    let postDataContainer = $("<div></div>");
    $(postDataContainer).addClass("post_data_container");

    $(content).append(messageContainer);

    //mostramos el contenido del posteo con el cual se ha interactuado
    $(content).append(showPostContent(post,postDataContainer))

    return content;
}

//mostramos el conenido del posteo
function showPostContent(post,container){
    if(post.Message){
        $(container).append(showMessagePost(post.Message));
    }
    if(post.multimedia_post.length > 0){
        $(container.append(showLinkImgPost(post.multimedia_post)));
    }
    return container;
}

//mostramos el mensaje del posteo
function showMessagePost(message){
    let messageContainer = $("<div></div>");
    $(messageContainer).addClass("message_post_container");
    let messageContent = $("<p></p>");
    $(messageContent).text(message);

    $(messageContainer).append(messageContent);

    return messageContainer;
}

//en caso de que el posteo tenga imagenes relacionadas, mostramos los link de las mismas
function showLinkImgPost(multimedias){
    let multimediaContainer = $("<div></div>");
    $(multimediaContainer).addClass("multimedia_post_container");
    for(let i of multimedias){
        if(i.Url){
            let linkImg = i.Url;
            let paragraph = $("<p></p>");
            $(paragraph).text(`${linkImg}`);
    
            $(multimediaContainer).append(paragraph);
        }
    }
    return multimediaContainer;
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
    showNewNotification(e.notification);
})

//mostramos la nueva notificacion obtenida en tiempo real
function showNewNotification(i){
    //creamos el contenedor de la notificacion
    let newNotification = $("<a></a>");
    $(newNotification).addClass("notification");
    
    $(newNotification).attr("href", i.LinkPost);
            
    let username = i.action_user.personal_data.Nickname;
    let photoUser = i.action_user.profile.ProfilePhotoURL;
    let photoUserName = i.action_user.profile.ProfilePhotoName
    
    let action = i.Action;
    let post = i.post;
    
    //contenedor que contendra la foto del usuario en caso de que sea un comentario,o un corazon si es un like
    let containerType = $("<div></div>");
    $(containerType).addClass("action_container");
    
    //contenedor que contendra el contenido de la notificacion
    let mainNotificationContainer = $("<div></div>");
    $(mainNotificationContainer).addClass("notification_content_container");
    
    let message = "";
    if(action != "Like"){
        //en caso de ser un comentario mostramos la foto del usuario
        message = "Ha comentado tu posteo"
        commentNotification(photoUser,photoUserName,username,containerType,mainNotificationContainer);
        $(mainNotificationContainer).append(showContent(message,username,post));
    }
    else{
        //en caso de ser un mensaje agregamos el corazon junto al contenido del posteo
        message = "indicó que le gusta tu posteo"
        likeNotification(photoUser,photoUserName,username,containerType,mainNotificationContainer)
        $(mainNotificationContainer).append(showContent(message,username,post));
    }
    $(newNotification).append(containerType);
    $(newNotification).append(mainNotificationContainer);
    $(notificationsContainer).prepend(newNotification);
}