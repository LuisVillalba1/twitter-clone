import * as utilsPosts from "../utils/utilsPosts.js";
import * as utilsIntersection from "../utils/utilsIntersection.js"

const loaderContainer = $(".loader_container");

//obtenemos la informacion del post
async function getPostData(){
    let link = window.location.href + "/details";
    try{
    //mientras se obtiene la informacion mostramos el loader
    $(loaderContainer).css("display", "flex");
    let data = await $.ajax({
        type : "GET",
        url : link
    })
    $(".post_container").css("display", "block");
    //mostramos la informacion
    console.log(data);
    showData(data);
    }
    catch(e){
        console.log(e);
    }
    //una ves finalizada la peticion ocultamos el loader
    finally{
        $(loaderContainer).css("display", "none");
    }
}

getPostData();

async function showData(data){
    //mostramos datos del usuario y el mensaje del post
    let userName = data.user.personal_data.Nickname;
    let logo = userName[0].toUpperCase();
    $(".logo").text(logo);

    $(".user_nickname").text(userName);

    let message = data.Message;

    if(message.length > 0){
        $(".user_message").text(message);
    }
    let multimedia = data.multimedia_post;

    if(multimedia.length > 0){
        showMultimedia(multimedia)
    }

    let interactionContainer = createInteraction(data);

    let interactions = $(interactionContainer[0].lastElementChild);
    utilsPosts.postYetInteraction(interactions,data)
    for(let i of interactions.children()){
        let child = i;

        if($(child).hasClass("like_container")){
            utilsPosts.likePost(child)
            utilsPosts.postYetLiked(child,data.likes);
        }
        if($(child).hasClass("save_container")){
            $(child).attr("method", "POST");
            $(child).attr("action", data.linkSave);
            utilsPosts.savePost(child);
            utilsPosts.postYetSave(child,data.safes);
        }
    }


    $(".current_post").css("display","block");

    utilsPosts.showRepostAndLikes(data.likes_count,data.likes_count);
    utilsPosts.showVisualizations(data.visualizations_count);

    let comments = data.comments
    showCommenst(comments)

}

//mostramos el contido multimedia en caso de que exista
function showMultimedia(data){
    for(let i in data){
        let currentMultimedia = data[i];

        let imgContainer = $("<div></div>");
        $(imgContainer).addClass("multimedia_img_container");

        let img = $("<img></img>");

        $(img).attr("alt",currentMultimedia.Name);
        $(img).attr("src", currentMultimedia.Url);

        $(imgContainer).append(img);

        $(".user_multimedia_container").append(imgContainer);
    }
}

const commentsContainer = $(".comments_post_container");
function showCommenst(commentsData){
    //mostramos los comentarios de 6 en 6
    let currentIndex = 0;
    let commentPerPage = 6;

    function showMoreComments(currentIndex,commentPerPage){
        //obtenemos de 6 en 6 los comentarios
        let max = Math.min(currentIndex + commentPerPage,commentsData.length);

        let comments = commentsData.slice(currentIndex,max);

        //en caso de que ya no existan mas comentarios detenemos la ejecucion
        if(comments.length == 0){
            console.log("end");
            return
        }
        //por cada comentarios mostramos la informacion correspondiente
        comments.forEach(currentComment=>{
            let linkPost = currentComment.linkPost;
            let commentContainer = $("<a></a>");
            $(commentContainer).attr("href", linkPost);
            $(commentContainer).addClass("comment_post_constainer");
    
            let nickname = currentComment.user.personal_data.Nickname;
            let postID = currentComment.PostID;

            let userDataContainer = $("<div></div>");
            $(userDataContainer).addClass("user_data_container");

            let nicknameNoSpaces = utilsPosts.deleteSpaces(nickname);
            
            let message = currentComment.Message;

            $(userDataContainer).append(utilsPosts.logoContainerShow(nickname));
            $(commentContainer).append(userDataContainer);
            
            let postContent = utilsPosts.showNameAndMessage(nickname,message);
            $(userDataContainer).append(postContent);

            let multimedia = currentComment.multimedia_post;

            if(multimedia && multimedia.length > 0){
                $(postContent).append(utilsPosts.showMultimedia(multimedia));
            }

            //obtenemos todas las interacciones
            let interaction_container = utilsPosts.showInteraction(currentComment.linkLike,currentComment.linkComment,currentComment.linkVisualization);

            $(commentContainer).append(userDataContainer);
            $(commentContainer).append(interaction_container);

            $(commentsContainer).append(commentContainer);

            let likeContainer = (interaction_container).children(".like_container");

            utilsPosts.postYetInteraction(interaction_container,currentComment);
            utilsPosts.postYetLiked(likeContainer,currentComment.likes);
            utilsPosts.likePost(likeContainer);

            utilsPosts.countIcon(currentComment,interaction_container);
        })
        currentIndex += commentPerPage;
        //verificamos que el ultimo comentario sea visible para mostrar mas
        utilsIntersection.createIntersectionObserver(".comment_post_constainer",showMoreComments.bind(null,currentIndex,commentPerPage))
    }

    showMoreComments(currentIndex,commentPerPage);
}



//creamos las interacciones del post
function createInteraction(data){

    let interactionContainer = $(".interaction_post_container");
    let interaction = `<div class="interaction">
    <a class="comments_container interaction_container" href=${data.linkComment}>
    <div class="interaction_icon_container">
        <i class="fa-regular fa-comment interaction_icon"></i>
    </div>
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
</form>
<form class="save_container interaction_container">
    <div class="save_bg">
        <div class="save_icon">
        </div>
    </div>
</form>
<div class="visualizations_container interaction_container">
    <div class="interaction_icon_container">
        <i class="fa-solid fa-chart-simple interaction_icon"></i>
    </div>
</div>
</div>
    `
 $(interactionContainer).append(interaction);
 return interactionContainer;
}
