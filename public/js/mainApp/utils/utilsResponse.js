export function showComment(data,mainContent,mainContainer){
    //mostramos datos del usuario como su nombre e imagen
    let userName = data.user.personal_data.Nickname;
    let urlImg = data.user.profile.ProfilePhotoURL;
    let nameImg = data.user.profile.ProfilePhotoName;

    let container = $("<div></div>");
    $(container).addClass("current_post");
    //añadimos la imagen del usuario en caso de que contenga
    $(container).append(showUserImg(userName,urlImg,nameImg));

    let linkProfile = data.linkProfile;
    let message = data.Message;
    let linkProfileParent = data.parent.linkProfile;
    let parentUsername = data.parent.user.personal_data.Nickname;
    let multimedia = data.multimedia_post;

    $(container).append(showContent(linkProfile,linkProfileParent,message,$username,parentUsername,multimedia));
    $(container).append(showMessage(message))
    $(container).append(showMultimedia(multimedia))

}

function showUserImg(userName,urlImg,nameImg){
    let userDataContainer = $("<div></div>");
    $(userDataContainer).addClass("user_data_container");

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

    $(ownerLogoContainer).append(ownerLogo);
    return $(userDataContainer).append(ownerLogoContainer);
}

//mostraos el contenido del posteo
function showContent(linkProfile,linkProfileResponse,message,username,parentUsername,multimedia){
    let content = $("<div></div>");
    $(content).addClass("content");

    $(content).append(showUser(username,linkProfile));
    $(content).append(showToResponse(parentUsername,linkProfileResponse))
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