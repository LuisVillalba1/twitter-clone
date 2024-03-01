import * as utilsPosts from "../utils/utilsPosts.js";
import * as utilsIntersection from "../utils/utilsIntersection.js";
import {createErrorAlert} from "../utils/error/errorAlert.js"

const profileContainer = $(".profile_container")

function getAnswers(){
    $.ajax({
        type: "GET",
        url: window.location.href + "/details",
        success: function (response) {
            showAnswers(response)
            console.log(response)
        },
        error:function(error){
            createErrorAlert(error.responseJSON.errors,profileContainer)
        }
    });
    $(".loader_container").css("display","none")
}

getAnswers();

const allPost = $(".posts_container");

function showAnswers(info){
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
            //creamos el contenedor del posteo
            let postContainer = $("<div></div>");
            $(postContainer).addClass("current_post");

            //obtenemos el link del usuario
            let parentUserLink = currentPost.parent.linkUser;

            //mostramos la informacion del posteo padre
            $(postContainer).append(showParentData(currentPost.parent));

            //obtenemos el nombre de a quien se responde
            let userResponse = currentPost.parent.user.personal_data.Nickname;

            //obtenemos todos los links para las interacciones y mostrar el post
            let linkPost = currentPost.linkPost;
            let linkLike = currentPost.linkLike;
            let linkVisualization = currentPost.linkVisualization;
            let linkComment = currentPost.linkComment;
         
            let nickname = currentPost.user.personal_data.Nickname;
            let postID = currentPost.PostID;
            
            //obtenemos el mensaje y el contenido multimedia de la respuesta
            let message = currentPost.Message;
            let multimedia = currentPost.multimedia_post;
            
            //creamos el contenedor que va conteneder la informacion del usuario
            let userDataContainer = $("<a></a>");
            $(userDataContainer).data("href", linkPost);
            $(userDataContainer).addClass("user_data_container");
            
            //mostramos la imagen del usuario y el contenido del posteo
            $(userDataContainer).append(showImgUser(nickname));
            $(userDataContainer).append(showContent(nickname,userResponse,message,multimedia,parentUserLink));
            
            let interactionContainer = utilsPosts.showInteraction(linkLike,linkComment,linkVisualization)

            $(postContainer).append(userDataContainer);
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
        utilsIntersection.createIntersectionObserver(".current_post",false,showMorePosts.bind(null,currentIndex,postPerPage))
     }
     showMorePosts(currentIndex,postPerPage)
}

//mostramos la informacion de los padres
function showParentData(parentData){
    let username = parentData.user.personal_data.Nickname;
    let linkUser = parentData.linkUser;
    let parentDataContainer = $("<div></div>");
    $(parentDataContainer).addClass("parent_data_container");
    
    $(parentDataContainer).append(showImgParent(username));

    let linkParent = parentData.linkPost;
    let message = parentData.Message;
    let multimedia = parentData.multimedia_post;
    $(parentDataContainer).append(showContentParent(linkParent,message,multimedia,username,linkUser));

    return parentDataContainer;
}

//creamos los contenedores para mostrar la imagen del padre del post
function showImgParent(username){
    let firsLetterUser = username[0].toUpperCase();
    let parentUser = $("<div></div>");
    $(parentUser).addClass("parent_user");

    let parentImgContainer = $("<div></div>");

    $(parentImgContainer).addClass("parent_img_container");

    let logo = $("<h4></h4>");

    $(logo).addClass("logo");

    $(logo).text(firsLetterUser);

    let userLane = $("<div></div>");
    $(userLane).addClass("parent_lane");

    $(parentImgContainer).append(logo);
    $(parentUser).append(parentImgContainer);
    $(parentUser).append(userLane);

    return parentUser;
}

//mostramos el contenido del posto padre
function showContentParent(linkParent,message,multimedia,nickname,linkUser){
    let parentPostContent = $("<a></a>");
    $(parentPostContent).addClass("parent_post_content");

    $(parentPostContent).attr("href", linkParent);

    //contenedor del nombre del usuario
    let parentNicknameContainer = $("<div></div>");
    $(parentNicknameContainer).addClass("parent_nickname_container");
    let parentNickname = $("<a></a>")
    $(parentNickname).addClass("parent_nickname");
    $(parentNickname).text(nickname);
    $(parentNickname).attr("href", linkUser);

    $(parentNicknameContainer).append(parentNickname);

    //contenedor del mensaje del posteo
    let parentMessageContainer = $("<div></div>");
    $(parentMessageContainer).addClass("parent_message_container");
    let parentMessage = $("<p></p>");
    $(parentMessage).addClass("parent_message");

    //en caso de que exista el mensage lo mostramos
    if(message){
        $(parentMessage).text(message);
    }

    $(parentMessageContainer).append(parentMessage);

    //contenedor del contenido multimedia
    let parentMultimediaContainer = $("<div></div>");
    $(parentMultimediaContainer).addClass("parent_multimedia_container");

    //en caso de que exista un contenido multimedia lo mostramos
    if(multimedia && multimedia.length > 0){
        showMultimedia(multimedia,parentMultimediaContainer)
    }

    //agregamos al contenedor del contenido cada unos de los contedores correspondientes
    $(parentPostContent).append(parentNicknameContainer);
    $(parentPostContent).append(parentMessageContainer);
    $(parentPostContent).append(parentMultimediaContainer);

    return parentPostContent;
}

//mostramos el contenido multimedia de un posteo padre
function showMultimedia(data,container){
    for(let i in data){
        let currentMultimedia = data[i];

        let imgContainer = $("<div></div>");
        $(imgContainer).addClass("multimedia_img_container");

        let img = $("<img></img>");

        $(img).attr("alt",currentMultimedia.Name);
        $(img).attr("src", currentMultimedia.Url);

        $(imgContainer).append(img);

        $(container).append(imgContainer);
    }
}

//mostramos la imagen del usuario
function showImgUser(username){
    let firsLetterUser = username[0].toUpperCase();
    let logoContainer = $("<div></div>");
    $(logoContainer).addClass("owner_logo_container");
    
    let ownerLogo = $("<div></div>");
    $(ownerLogo).addClass("owner_logo");

    let logo = $("<h4></h4>");
    $(logo).addClass("logo");

    $(logo).text(firsLetterUser);

    $(ownerLogo).append(logo);
    $(logoContainer).append(ownerLogo);

    return logoContainer;
}

//mostramos el contenido del posteo
function showContent(username,userResponse,message,multimedia,responseUserLink){
    let contentContainer = $("<div></div>");
    $(contentContainer).addClass("content");

    //contenedor del nombre del usuario
    let nicknameContainer = $("<div></div>");
    $(nicknameContainer).addClass("user_nickname_container");
    let userNickname = $("<p></p>");
    $(userNickname).text(username);
    $(userNickname).addClass("user_nickname");

    $(nicknameContainer).append(userNickname);

    //contenedor de a quien se esta respondiendo el posteo correspondiente
    let responseContainer = $("<div></div>");
    $(responseContainer).addClass("response_container");
    let responseText = $("<p></p>");
    $(responseText).text("En respuesta a ");
    let responseUser = $("<a></a>");
    $(responseUser).addClass("response_post");
    //mostramos a quien se ha respondido
    $(responseUser).text("@" +userResponse);
    $(responseUser).attr("href", responseUserLink);

    $(responseContainer).append(responseText);
    $(responseContainer).append(responseUser);

    //contenedor del mensaje del posteo
    let userMessageContainer = $("<div></div>");
    $(userMessageContainer).addClass("user_message_container");
    let userMessage = $("<p></p>");
    $(userMessage).addClass("user_message");
    if(message){
        $(userMessage).text(message);
    }
    $(userMessageContainer).append(userMessage);

    //contenedor multimedia
    let multimediaContainer = $("<div></div>");
    $(multimediaContainer).addClass("user_multimedia_container");

    //en caso de que exista el contenido multimedia lo mostramos
    if(multimedia && multimedia.length > 0){
        showMultimedia(multimedia,multimediaContainer)
    }

    $(contentContainer).append(nicknameContainer);
    $(contentContainer).append(responseContainer);
    $(contentContainer).append(userMessageContainer);
    $(contentContainer).append(multimediaContainer);

    return contentContainer;
}

//creamos las interacciones del post
function createInteraction(data,container){
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
<div class="visualizations_container interaction_container">
    <div class="interaction_icon_container">
        <i class="fa-solid fa-chart-simple interaction_icon"></i>
    </div>
</div>
</div>
    `
 $(container).append(interaction);
 return container;
}