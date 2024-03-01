
import * as utilsIntersection from "../../utils/utilsIntersection.js";
import * as utilsPost from "../../utils/utilsPosts.js";
import { ocultLoader } from "../../utils/utilLoader.js";
import {createErrorAlert} from "../../utils/error/errorAlert.js"

const savesPostContainer = $(".saves_posts_container");
async function getBookmarks(){
    try{
        const data = await $.ajax({
            type: "get",
            url: window.location.href + "/details",
        });
        showPosts(data);
    }
    catch(e){
        console.log(e);
        createErrorAlert(e.responseJSON.errors,savesPostContainer)
    }
    finally{
        ocultLoader();
    }
}


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
            let linkPost = currentPost.post.linkPost;
            let linkLike = currentPost.post.linkLike;
            let linkVisualization = currentPost.post.linkVisualization;
            let linkComment = currentPost.post.linkComment;
        
            //a cada post le agregamos un id con su nickname y el numero de post
            let postContainer = $("<a></a>");
            $(postContainer).addClass("post");
            $(postContainer).attr("href", linkPost);
        
            let nickname = currentPost.post.user.personal_data.Nickname;
            let postID = currentPost.PostID;
        
            let nicknameNoSpaces = utilsPost.deleteSpaces(nickname);
            $(postContainer).attr("id",`${nicknameNoSpaces}-${postID}`);
        
            let message = currentPost.post.Message;
        
            let userDataContainer = $("<div></div>");
            $(userDataContainer).addClass("user_data_container");
            
            //obtenemos la imagen del usuario
            let userImg = currentPost.post.user.profile.ProfilePhotoURL;
            let nameUserImg = currentPost.post.user.profile.ProfilePhotoName;

            let linkProfile = currentPost.post.linkProfile;

            //mostramos el logo del usuario
            $(userDataContainer).append(utilsPost.logoContainerShow(nickname,userImg,nameUserImg));
            $(postContainer).append(userDataContainer);
            //mostramos el mensaje del usuario en caso de que exista
            let postContent = utilsPost.showNameAndMessage(nickname,message,linkProfile);
        
            $(userDataContainer).append(postContent);
        
        
            let multimedia = currentPost.post.multimedia_post;
            if(multimedia && multimedia.length > 0){
                //mostramos el contenido multimedia
                $(postContent).append(utilsPost.showMultimedia(multimedia));
            }
            //obtenemos las interacciones y lo agregamos al post content
            let interactionContainer = showInteraction(currentPost.post);
        
            $(postContainer).append(userDataContainer);
            //agregamos al contenedor del post el post container
            $(postContainer).append(interactionContainer);
        
            //agregamos todos los post al contenedor principal
            $(".saves_posts_container").append(postContainer);
        
            //Obtenemos el contenedor del like
            let likeContainer = $(interactionContainer).children(".like_container");
            let saveContainer = (interactionContainer).children(".save_container");
            let saveIcon = $(saveContainer).children().children(".save_icon");

            //obtenemos los contenedores de la suma de likes y guardados
            let likesCount = likeContainer.children(".likes_count");
            let saveCount = saveContainer.children(".safes_count");
        
            utilsPost.postYetInteraction(interactionContainer,currentPost.post)
            utilsPost.postYetLiked(likeContainer,currentPost.post.likes);
            utilsPost.likePost(likeContainer,likesCount,savesPostContainer);

            animateSave(saveIcon);
            utilsPost.savePost(saveContainer,saveCount,savesPostContainer);
        
            utilsPost.countIcon(currentPost.post,interactionContainer);
        })
        //aumentamos el indice luego de mostrar los posts
        currentIndex += postPerPage
        //verificamos si el ultimo post mostrado es interceptado
        //utilizamos bind para poder llamar a esta funcion callback dentro de createIntersectionObserver con los argumentos necesarios
        utilsIntersection.createIntersectionObserver(".post",false,showMorePosts.bind(null,currentIndex,postPerPage))
    }
    showMorePosts(currentIndex,postPerPage)
}

function showInteraction(data){
    
    let interaction = `<div class="interaction">
    <a class="comments_container interaction_container" href=${data.linkComment}>
    <div class="interaction_icon_container">
        <i class="fa-regular fa-comment interaction_icon"></i>
    </div>
    <p class="comments_count"></p>
</a>
<div class="repost_container interaction_container">
    <div class="interaction_icon_container">
        <i class="fa-solid fa-repeat interaction_icon"></i>
    </div>
</div>
<form class="like_container interaction_container" method="POST" action=${data.linkLike}>
    <div class="heart_bg">
        <div class="heart_icon">

        </div>
    </div>
    <p class="likes_count"></p>
</form>
<form class="save_container interaction_container" method="POST" action=${data.linkSave}>
    <div class="save_bg">
        <div class="save_icon">
        </div>
    </div>
    <p class="safes_count"></p>
</form>
<div class="visualizations_container interaction_container">
    <div class="interaction_icon_container">
        <i class="fa-solid fa-chart-simple interaction_icon"></i>
    </div>
    <p class="visualizations_count"></p>
</div>
</div>
    `
 return $(interaction);
}


function animateSave(icon){
    $(icon).css("animation","save-anim 0.5s steps(20) forwards");
}

getBookmarks()