import * as utilsPosts from "../utils/utilsPosts.js";
import * as utilsIntersection from "../utils/utilsIntersection.js";
import {createErrorAlert} from "../utils/error/errorAlert.js"

//seteamos el link de respuestas
$(".respuestas_location").children().attr("href",window.location.href + "/answers")

const profileContainer = $(".profile_container");

//obtenemos los posteos correspondientes
function getUserPost(){
    $.ajax({
        type: "GET",
        url: window.location.href + "/posts",
        success: function (response) {
           showPosts(response)
        },
        error: function(error){
            createErrorAlert(error.responseJSON.errors,profileContainer);
        }
    });
    //ocultamos el loader
    $(".loader_container").css("display", "none");
}

const allPost = $(".posts_container");

getUserPost();

//mostramos los posteos
function showPosts(info){
    //la idea es mostrar los posts de 6 en 6
    let currentIndex = 0;
    let postPerPage = 6;

    //mostramos los posts
    function showMorePosts(currentIndex,postPerPage){
        //obtenemos de 6 en 6 los posts
        let max = Math.min(currentIndex + postPerPage,info.length);

        let posts = info.slice(currentIndex,max);

        //en caso de que ya no existan mas post dentemos la ejecucion
        if(posts.length == 0){
            return
        }
        //iteramoc sobre cada post
        posts.forEach(currentPost=>{
            //obtenemos todos los links para las interacciones y mostrar el post
            let linkPost = currentPost.linkPost;
            let linkLike = currentPost.linkLike;
            let linkVisualization = currentPost.linkVisualization;
            let linkComment = currentPost.linkComment;
        
            //a cada post le agregamos un id con su nickname y el numero de post
            let postContainer = $("<a></a>");
            $(postContainer).addClass("post");
            $(postContainer).attr("href", linkPost);
        
            let nickname = currentPost.user.personal_data.Nickname;
            let postID = currentPost.PostID;
        
            let nicknameNoSpaces = utilsPosts.deleteSpaces(nickname);
            $(postContainer).attr("id",`${nicknameNoSpaces}-${postID}`);
        
            let message = currentPost.Message;
        
            let userDataContainer = $("<div></div>");
            $(userDataContainer).addClass("user_data_container");
        
            //mostramos el logo del usuario
            $(userDataContainer).append(utilsPosts.logoContainerShow(nickname));
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
        //aumentamos el indice luego de mostrar los posts
        currentIndex += postPerPage
        //verificamos si el ultimo post mostrado es interceptado
        //utilizamos bind para poder llamar a esta funcion callback dentro de createIntersectionObserver con los argumentos necesarios
        utilsIntersection.createIntersectionObserver(".post",false,showMorePosts.bind(null,currentIndex,postPerPage))
    }
    showMorePosts(currentIndex,postPerPage)
}