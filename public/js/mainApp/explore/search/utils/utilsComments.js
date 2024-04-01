import * as utilsPosts from "../../../utils/utilsPosts.js";

export function showComment(data,mainContent,mainContainer){
    //mostramos datos del usuario como su nombre e imagen
    let userName = data.user.personal_data.Nickname;
    let urlImg = data.user.profile.ProfilePhotoURL;
    let nameImg = data.user.profile.ProfilePhotoName;

    let container = $("<a></a>");
    $(container).addClass("current_post");

    let userDataContainer = $("<div></div>");
    $(userDataContainer).addClass("user_data_container");

    //añadimos la imagen del usuario en caso de que contenga
    $(userDataContainer).append(showUserImg(userName,urlImg,nameImg));

    let content = $("<div></div>");
    $(content).addClass("content");

    let linkProfile = data.linkProfile;
    let message = data.Message;
    let linkProfileParent = data.parent.linkProfile;
    let parentUsername = data.parent.user.personal_data.Nickname;
    let multimedia = data.multimedia_post;
    let linkPost = data.linkPost;

    $(container).attr("href", linkPost);


    //mostramos la imagen,el mensage y el contenido multimedia
    $(content).append(showUser(userName,linkProfile));
    $(content).append(showToResponse(parentUsername,linkProfileParent))
    $(content).append(showMessage(message))
    $(content).append(showMultimedia(multimedia))

    $(userDataContainer).append(content);
    $(container).append(userDataContainer);

    //obtenemos las interacciones
    $(container).append(createInteraction(data));

    $(mainContent).append(container);

    utilsPosts.postYetInteraction($(".interaction:last"),data)


    for(let i of $(".interaction:last").children()){
        let child = i;

        if($(child).hasClass("like_container")){
            utilsPosts.likePost(child,null,mainContainer)
            utilsPosts.postYetLiked(child,data.likes);
        }
        if($(child).hasClass("save_container") && data.linkSave){
            $(child).attr("method", "POST");
            $(child).attr("action", data.linkSave);
            utilsPosts.savePost(child,null,mainContainer);
            utilsPosts.postYetSave(child,data.safes);
        }
    }
}

function showUserImg(userName,urlImg,nameImg){

    let ownerLogoContainer = $("<div></div>");
    $(ownerLogoContainer).addClass("owner_logo_container");

    let ownerLogo = $("<div></div>");
    $(ownerLogo).addClass("owner_logo");

    if(urlImg && nameImg){
        let img = $("<img></img>");
        $(img).attr("src", urlImg);
        $(img).attr("alt",nameImg);

        $(ownerLogo).append(img);
    }
    else{
        let logo = $("<h4></h4>");
        $(logo).addClass("logo");
        $(logo).text(userName[0].toUpperCase());
        $(ownerLogo).append(logo);
    }

    return $(ownerLogoContainer).append(ownerLogo);
}

//mostramos el nombre del usuario
function showUser(username,linkProfile){
    let userNicknameContainer = $("<div></div>");
    $(userNicknameContainer).addClass("user_nickname_container");

    let linkContainer = $("<a></a>");
    $(linkContainer).addClass("user_nickname");
    $(linkContainer).attr("href", linkProfile);
    $(linkContainer).text(username);

    return $(userNicknameContainer).append(linkContainer);
}

//mostramos a quien se responde
function showToResponse(parentUser,parentLink){
    let responseContainer = $("<div></div>");
    $(responseContainer).addClass("response_container");

    let paragraphtResponse = $("<p></p>");
    $(paragraphtResponse).text("En respuesta a");
    
    let responsePost = $("<a></a>");
    $(responsePost).addClass("response_post");
    $(responsePost).attr("href", parentLink);
    $(responsePost).text(`@${parentUser}`);

    $(responseContainer).append(paragraphtResponse);
    return $(responseContainer).append(responsePost);
}

function showMessage(message){
    let userMessageContainer = $("<div></div>");
    $(userMessageContainer).addClass("user_message_container");

    let userMessage = $("<p></p>");
    $(userMessage).addClass("user_message");
    $(userMessage).text(message);


    return $(userMessageContainer).append(userMessage);

}

function showMultimedia(data){
    let userMultimediaContainer = $("<div></div>")
    $(userMultimediaContainer).addClass("user_multimedia_container");

    if(data.length > 0){
        for(let i in data){
            let currentMultimedia = data[i];
    
            let imgContainer = $("<div></div>");
            $(imgContainer).addClass("multimedia_img_container");
    
            let img = $("<img></img>");
    
            $(img).attr("alt",currentMultimedia.Name);
            $(img).attr("src", currentMultimedia.Url);
    
            $(imgContainer).append(img);
    
            $(userMultimediaContainer).append(imgContainer);
        }
    }

    return userMultimediaContainer;
}

//creamos las interacciones del post
function createInteraction(data){

    let interaction = `<div class="interaction">
    <a class="comments_container interaction_container" href=${data.linkComment}>
    <div class="interaction_icon_container">
        <i class="fa-regular fa-comment interaction_icon"></i>
    </div>
    <div class="count_interaction_container">
    <p class="comments_count">${data.comments_count}</p>
</div>
</a>
<div class="like_container interaction_container" id=${data.linkLike}>
    <div class="heart_bg">
        <div class="heart_icon">

        </div>
    </div>
    <div class="count_interaction_container">
        <p class="likes_count count_interaction">${data.likes_count}</p>
    </div>
</div>
<div class="visualizations_container interaction_container" id=${data.linkVisualization}>
    <div class="interaction_icon_container">
        <i class="fa-solid fa-chart-simple interaction_icon"></i>
    </div>
    <div clas="count_interaction_container">
        <p class="visualizations_count count_interaction">${data.visualizations_count}</p>
    </div>
</div>
</div>
    `
 return interaction;
}

