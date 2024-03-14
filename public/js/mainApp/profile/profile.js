import * as utilsPosts from "../utils/utilsPosts.js";
import * as utilsIntersection from "../utils/utilsIntersection.js";
import {createLoader} from "../utils/createLoader.js"
import { createErrorAlert, setOpacityChilds,removeOpacity} from "../utils/error/errorAlert.js";


//seteamos el link de respuestas
$(".respuestas_location").children().attr("href",window.location.href + "/answers")
$(".me_gusta_location").children().attr("href",window.location.href + "/likes")

const profileContainer = $(".profile_container");


//obtenemos los posteos correspondientes
async function getUserPost(url){
    $(".profile_container").append(createLoader)
    try{
        const response = await $.ajax({
            type: "get",
            url: url,
        })
        if(!response.data){
            return
        }
        showPosts(response.data,response.next_page_url)
    }
    catch(e){
        if(e.responseJSON){
            return createErrorAlert(e.responseJSON.errors,profileContainer)
        }
        createErrorAlert(e,profileContainer)
    }
    finally{
        $(".loader_container").remove();
    }
}

const allPost = $(".posts_container");

await getUserPost(window.location.href + "/posts");

//mostramos los posteos
function showPosts(info,url){
    info.forEach(currentPost=>{
        //obtenemos todos los links para las interacciones y mostrar el post
        let linkPost = currentPost.linkPost;
        let linkLike = currentPost.linkLike;
        let linkVisualization = currentPost.linkVisualization;
        let linkComment = currentPost.linkComment;
    
        //a cada post le agregamos un id con su nickname y el numero de post
        let postContainer = $("<a></a>");
        $(postContainer).addClass("post");
        $(postContainer).attr("href", linkPost);
        $(postContainer).attr("id",currentPost.PostID)
    
        let nickname = currentPost.user.personal_data.Nickname;
        let postID = currentPost.PostID;
    
    
        let message = currentPost.Message;
    
        let userDataContainer = $("<div></div>");
        $(userDataContainer).addClass("user_data_container");
    
        let imgUser = currentPost.user.profile.ProfilePhotoURL;
        let imgName = currentPost.user.profile.ProfilePhotoName

        //mostramos el logo del usuario
        $(userDataContainer).append(utilsPosts.logoContainerShow(nickname,imgUser,imgName));
        $(postContainer).append(userDataContainer);
        //mostramos el mensaje del usuario en caso de que exista
        let postContent = utilsPosts.showNameAndMessage(nickname,message);
    
        $(userDataContainer).append(postContent);
    
    
        let multimedia = currentPost.multimedia_post;
        if(multimedia && multimedia.length > 0){
            //mostramos el contenido multimedia
            $(postContent).append(utilsPosts.showMultimedia(multimedia));
        }
        //obtenemos las interacciones y lo agregamos al post content
        let interactionContainer = utilsPosts.showInteraction(linkLike,linkComment,linkVisualization);
    
        $(postContainer).append(userDataContainer);
        //agregamos al contenedor del post el post container
        $(postContainer).append(interactionContainer);
    
        //agregamos todos los post al contenedor principal
        $(allPost).append(postContainer);
    
        //Obtenemos el contenedor del like y la suma de likes que contiene el mismo
        let likeContainer = $(interactionContainer).children(".like_container");
        let likesCount = $(likeContainer).children().children(".likes_count")
    
        utilsPosts.postYetInteraction(interactionContainer,currentPost)
        utilsPosts.postYetLiked(likeContainer,currentPost.likes);
        utilsPosts.likePost(likeContainer,likesCount,profileContainer);
    
        utilsPosts.countIcon(currentPost,interactionContainer);
    })
    //obtenemos mas posteos una ves que se haya visualizado el ultimo
    utilsIntersection.createIntersectionObserver(".post",false,false,getUserPost.bind(null,url))
}

const followUserContainer = $(".follow_user_container");

//permitimos seguir o no seguir al usuario
function followOrUnfollow(){
    if(followUserContainer){
        $(followUserContainer).on("click", async function () {
            checkFollowOrUnfollow();
        });
    }
}

//cambiamos el contenido del contenedor de follow
function changeFollowContent(response){
    let children = followUserContainer.children();
    $(children).text(response.message);

    if(response.message == "Dejar de seguir"){
        return $(followUserContainer).attr("id", "unfollow");
    }
    $(followUserContainer).attr("id","follow")
}

//realizamos la solicitud para seguir al usuario
async function fechtFollow(){
    try{
        const response = await $.ajax({
            type : "post",
            url : window.location.href + "/follow"
        });
        return changeFollowContent(response);
    }
    catch(e){
        console.log(e);
    }
}

followOrUnfollow()

//verificamos si hay que seguir o dejar de seguir al usuario
//en caso de que se se desee dejar de seguir mostramos una alerta
async function checkFollowOrUnfollow(){

    if($(followUserContainer).attr("id") == "unfollow"){
        //creamos la alerta para dejar de seguir al usuario
        let user = $(".nickname_and_name_container").children()[1];
        createAlertUnfollow($(user).text());
    }
    else{
        return await fechtFollow(); 
    }
}


function createAlertUnfollow(user){
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
    $(profileContainer).append(alertContainer);

    //perimitimos cancelar la operacion o dejar de seguir al usuario
    setOpacityChilds(profileContainer,alertContainer)
    unfollow(unfollowContainer,alertContainer);
    closeAlertUnfollow(cancelContainer,alertContainer);
}

//dejamos de seguir al usuario
function unfollow(container,alertContainer){
    $(container).on("click", async function () {
        removeAlertAndOpactity(alertContainer);
        await fechtFollow()
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
    removeOpacity(profileContainer,alertContainer)
}