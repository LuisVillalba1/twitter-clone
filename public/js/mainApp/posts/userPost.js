import * as utilsPosts from "../utils/utilsPosts.js";

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
    //mostramos la informacion
    console.log(data);
    showData(data)
    }
    catch(e){
        console.log(e);
    }
    //una ves finalizada la peticion ocultamos el loader
    $(loaderContainer).css("display", "none");
}

getPostData();

function showData(data){
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

    let interactions = interactionContainer[0].lastElementChild;
    postYetInteraction(interactions,data)
    for(let i of interactions.children){
        let child = i;

        if($(child).hasClass("like_container")){
            likePost(child)
            postYetLiked(child,data.likes)
        }
    }

    let comments = data.comments
    showCommenst(comments)

}


function postYetInteraction(interactionContainer,data){
    let interaction = $(interactionContainer);
    //interamos sobre todos los hijos del interactionContainer
    $.each(interaction.children(), function (indexInArray, valueOfElement) { 
        //obtenemos el valor de la clase
         let valueClass = $(valueOfElement).attr("class");

         if(!valueClass.includes("like_container")){
            //obtenemos la primera clase, la cual va a contener el valor de la propiedad de nuestro objeto
            let valueClassSplit = valueClass.split(" ")
            let ultimoGuionIndex = valueClassSplit[0].lastIndexOf("_");
            let property =valueClassSplit[0].substring(0,ultimoGuionIndex);
            //en caso de que ya se haya visto,comentado, o resposteado el post marcamos el icono en cuestion de color
            updateInteracction(data[property],valueOfElement)
         }
    });
}

function updateInteracction(data,element){
    if(data && data.length > 0){
        let child = $(element).children()[0];
        let icon = $(child).children()[0];
        
        $(icon).css("color", "rgb(57, 179, 255)");
    }
}

//verificamos si el posteo ya esta likeado
function postYetLiked(likeContainer,info){
    //obtenemos el icono del corazon
    let heartContainer = $(likeContainer).children(".heart_bg");
    let heart = $(heartContainer).children(".heart_icon")
    if(info.length >= 1){
        $(heart).css("animation","like-anim 0.5s steps(28) forwards");
    }
}

//likeamos el post
async function likePost(likeContainer){
    let heartContainer = $(likeContainer).children(".heart_bg");
    let hearstIcon = $(heartContainer).children(".heart_icon")

    $.each(hearstIcon, function (indexInArray, valueOfElement) {
         $(valueOfElement).on("click", async function (e) {
            e.preventDefault();
            let padre = $(e.target).closest(".like_container");
            let action = $(padre).attr("action");
            let response = await sendLike(action);
            if(response){
                $(e.target).css("animation","like-anim 0.5s steps(28) forwards");
            }
            else{
                $(e.target).removeAttr("style");
            }
         });
    });
}

//hacemos la solicitud para likear el post
async function sendLike(action){
    try {
        const response = await $.ajax({
            type: "POST",
            url: action
        });
        return response
    } catch (error) {
        console.log(error.responseJSON.message);
    }
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
function showCommenst(comments){
    if(!comments.length <= 0){
        for(let i in comments){
            let currentComment = comments[i]
            let commentContainer = $("<a></a>");
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

            let interaction_container = utilsPosts.showInteraction(multimedia);

            $(commentContainer).append(userDataContainer);
            $(commentContainer).append(interaction_container);

            $(commentsContainer).append(commentContainer);
        }
    }
}

//creamos las interacciones del post
function createInteraction(data){

    let interactionContainer = $(".interaction_post_container");
    let interaction = `<div class="interaction">
    <a class="comments_container interaction_container" href=${data.linkComment}>
    <div class="interaction_icon_container">
        <i class="fa-regular fa-comment interaction_icon"></i>
    </div>
    <div class="count_interaction_container">
        <p class="comments_count count_interaction">${data.comments_count}</p>
    </div>
</a>
<div class="repost_container interaction_container">
    <div class="interaction_icon_container">
        <i class="fa-solid fa-repeat interaction_icon"></i>
    </div>
    <div class="count_interaction_container">
        <p class="visualizations_count count_interaction">${data.comments_count}</p>
    </div>
</div>
<form class="like_container interaction_container" method="POST" action=${data.linkLike}>
    <div class="heart_bg">
        <div class="heart_icon">

        </div>
    </div>
    <div class="count_interaction_container">
        <p class="likes_count count_interaction">${data.likes_count}</p>
    </div>
</form>
<div class="visualizations_container interaction_container">
    <div class="interaction_icon_container">
        <i class="fa-solid fa-chart-simple interaction_icon"></i>
    </div>
    <div class="count_interaction_container">
        <p class="visualizations_count count_interaction">${data.visualizations_count}</p>
    </div>
</div>
</div>
    `


 $(interactionContainer).append(interaction);
 return interactionContainer;
}