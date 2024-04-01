import * as utilsPosts from "../utils/utilsPosts.js";
import * as utilsIntersection from "../utils/utilsIntersection.js"
import {createErrorAlert} from "../utils/error/errorAlert.js";
import { createLoader } from "../utils/createLoader.js";

const postContainer = $(".post_container");

//obtenemos la informacion del post
async function getPostData(){
    let link = window.location.href + "/details";
    $(postContainer).append(createLoader())
    try{
    //mientras se obtiene la informacion mostramos el loader
    let data = await $.ajax({
        type : "GET",
        url : link
    })
    $(".post_container").css("display", "flex");
    //mostramos la informacion
    showData(data);
    }
    catch(e){
        createErrorAlert(e.responseJSON.errors,$(".post_container"))
    }
    //una ves finalizada la peticion ocultamos el loader
    finally{
        $(".loader_container").remove()
    }
}

getPostData();

async function showData(data){

    //en caso de que el posteo sea un comentario,mostramos los datos del post padre
    if(data.parent){
        showParentData(data.parent)
    }

    //mostramos datos del usuario como su nombre e imagen
    let userName = data.user.personal_data.Nickname;
    let urlImg = data.user.profile.ProfilePhotoURL;
    let nameImg = data.user.profile.ProfilePhotoName;

    showUserImg(userName,urlImg,nameImg);

    //obtenemos el link del perfil
    let linkProfile = data.linkProfile;
    $(".user_nickname").text(userName);
    $(".user_nickname").attr("href",linkProfile)

    let message = data.Message;

    //mostramos el mensaje en caso de que exista
    if(message && message.length > 0){
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
            utilsPosts.likePost(child,null,postContainer)
            utilsPosts.postYetLiked(child,data.likes);
        }
        if($(child).hasClass("save_container")){
            $(child).attr("id", data.linkSave);
            utilsPosts.savePost(child,null,postContainer);
            utilsPosts.postYetSave(child,data.safes);
        }
    }


    $(".current_post").css("display","block");

    utilsPosts.showRepostAndLikes(data.likes_count,data.likes_count);
    utilsPosts.showVisualizations(data.visualizations_count);

    await getComments(window.location.href + "/comments");

}

function showUserImg(nickname,urlImg,nameImg){
    if(urlImg && nameImg){
        let img = $("<img></img>");
        $(img).attr("src", urlImg);
        $(img).attr("alt",nameImg);

        $(".owner_logo").append(img);
    }
    else{
        let logo = $("<h4></h4>");
        $(logo).addClass("logo");
        $(logo).text(nickname[0].toUpperCase());
        $(".owner_logo").append(logo);
    }
}

function showParentData(parentData){
    //obteemos el nombre de usuario y la imagen del usuario
    let username = parentData.user.personal_data.Nickname;
    let userImg = parentData.user.profile.ProfilePhotoURL;
    let nameImg = parentData.user.profile.ProfilePhotoName;


    let parentUser = $(".parent_user");

    let parenImgContainer = $("<div></div>");

    $(parenImgContainer).addClass("parent_img_container");

    let userLane = $("<div></div>");

    $(userLane).addClass("parent_lane");

    //mostramos el logo del usuario
    $(parenImgContainer).append(showParentImg(username,userImg,nameImg));
    $(parentUser).append(parenImgContainer);
    $(parentUser).append(userLane);

    $(".parent_nickname").text(username);

    $(".parent_post_content").attr("href", parentData.linkPost);
    //mostramos el mensaje y el contenido multimedia en caso de que existan
    if(parentData.Message){
        $(".parent_message").text(parentData.Message)
    }

    let multimedia = parentData.multimedia_post;

    if(multimedia.length > 0){
        showMultimediaParent(multimedia);
    }

    let linkProfile = parentData.linkProfile;

    showResponse(username,linkProfile)
}

//en caso de que el usuario contenga una imagen la mostramos, si no mostramos la inicial de su nickname
function showParentImg(name,imgUrl,nameImg){
    if(imgUrl && nameImg){
        let img = $("<img></img>");
        $(img).attr("src", imgUrl);
        $(img).attr("alt",nameImg);

        return img;
    }
    else{
        let logo = $("<h4></h4>");
        $(logo).addClass(logo);
        $(logo).text(name[0].toUpperCase());

        return logo;
    }
}

//en caso de que sea un comentario mostramos que se responde a cierto usuario
function showResponse(nickname,linkProfile){
    $(".response_container").css("display", "flex");
    
    $(".response_post").text(`@` + nickname);
    $(".response_post").attr("href",linkProfile);
}

function showMultimediaParent(data){
    for(let i in data){
        let currentMultimedia = data[i];

        let imgContainer = $("<div></div>");
        $(imgContainer).addClass("multimedia_img_container");

        let img = $("<img></img>");

        $(img).attr("alt",currentMultimedia.Name);
        $(img).attr("src", currentMultimedia.Url);

        $(imgContainer).append(img);

        $(".parent_multimedia_container").append(imgContainer);
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

//obtnemos los comentarios
async function getComments(url){
    $(postContainer).append(createLoader())
    try{
        const response = await $.ajax({
            type : "get",
            url : url,
        })
        if(!response.data){
            return
        }
        showCommenst(response.data,response.next_page_url);
    }
    catch(e){
        console.log(e);
    }
    finally{
        $(".loader_container").remove()
    }
}

const commentsContainer = $(".comments_post_container");
function showCommenst(commentsData,url){
    commentsData.forEach(currentComment=>{
        //obtenemos el link del post y permitimos que el usuario pueda ir a ver el posteo
        let linkPost = currentComment.linkPost;
        let commentContainer = $("<a></a>");
        $(commentContainer).attr("href", linkPost);
        $(commentContainer).attr("id",currentComment.PostID);
        $(commentContainer).addClass("comment_post_constainer");

        //obtenemos el nombre del usuario
        let nickname = currentComment.user.personal_data.Nickname;

        let userDataContainer = $("<div></div>");
        $(userDataContainer).addClass("user_data_container");

        //obtnemos el mensaje
        let message = currentComment.Message;

        //obtenemos tanto la imagen del usuario como el link del perfil
        let userImg = currentComment.user.profile.ProfilePhotoURL;
        let userImgName = currentComment.user.profile.ProfilePhotoName;

        let linkProfile = currentComment.linkProfile;

        //mostramos la imagen del usuario 
        $(userDataContainer).append(utilsPosts.logoContainerShow(nickname,userImg,userImgName));
        $(commentContainer).append(userDataContainer);
        
        let postContent = utilsPosts.showNameAndMessage(nickname,message,linkProfile);
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
        utilsPosts.likePost(likeContainer,null,postContainer);

        utilsPosts.countIcon(currentComment,interaction_container);
    })
    //verificamos que el ultimo comentario sea visible para mostrar mas
    utilsIntersection.createIntersectionObserver(".comment_post_constainer",true,false,getComments.bind(null,url))
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
<form class="like_container interaction_container" method="POST" action=${data.linkLike}>
    <div class="heart_bg">
        <div class="heart_icon">

        </div>
    </div>
</form>
<div class="save_container interaction_container">
    <div class="save_bg">
        <div class="save_icon">
        </div>
    </div>
</div>
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
