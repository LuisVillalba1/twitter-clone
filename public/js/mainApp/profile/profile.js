import * as utilsPosts from "../utils/utilsPosts.js";
import * as utilsIntersection from "../utils/utilsIntersection.js";
import {createLoader} from "../utils/createLoader.js"
import { createErrorAlert } from "../utils/error/errorAlert.js";

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