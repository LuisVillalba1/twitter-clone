import {createErrorAlert} from "./error/errorAlert.js"

//eliminamos los espacios de un string
export function deleteSpaces(value){
    return value.replace(/ /g, "_");
}

//añadimos la imagen del usuario, por ahora solo sera la primera letra en mayuscula del nickname
export function logoContainerShow(Name,urlPhoto,photoName){
    let logoContainer = $("<div></div>");
    let imgContainer = $("<div></div>");
    $(logoContainer).addClass("owner_logo_container");
    $(imgContainer).addClass("owner_logo");
    if(urlPhoto && photoName){
        let img = $("<img></img>");
        $(img).attr("src", urlPhoto);
        $(img).attr("alt", photoName);
        $(imgContainer).append(img);
    }
    else{
        let firstLetter = $("<h4></h4>");
        $(firstLetter).text(Name[0].toUpperCase());
    
        $(imgContainer).append(firstLetter);
    }

    $(logoContainer).append(imgContainer);

    return logoContainer;
}

//creamos los contenedores del nombre de usuario y mensaje
export function showNameAndMessage(username,userMessage,linkProfile){
    let postContent = $("<div></div>");
    $(postContent).addClass("content");
    

    let nameContainer = $("<div></div>");
    $(nameContainer).addClass("user_nickname_container");
    let name = $("<a></a>");
    $(name).addClass("user_nickname");
    $(name).text(username);
    $(name).attr("href", linkProfile);

    $(nameContainer).append(name);

    $(postContent).append(nameContainer);
    if(userMessage){
        let content = $("<div></div>");
        $(content).addClass("user_message_container");
        let message = $("<p></p>");
        $(message).addClass("user_message");
        message[0].textContent = userMessage;
    
        $(content).append(message);
        $(postContent).append(content);

        return postContent;
    }

    return postContent;
}


//mostramos el contenido multimedia
export function showMultimedia(multimedia){
    let imgsContainer = $("<div></div>");
    $(imgsContainer).addClass("user_multimedia_container");
    for(let i in multimedia){
        let currenMultimedia = multimedia[i];
        let container = $("<div></div>");
        $(container).addClass("multimedia_img_container");
        let img = $("<img></img>");
        $(img).addClass("user_posts_img");
        $(img).attr("src", currenMultimedia.Url);
        $(img).attr("alt", currenMultimedia.Name);

        $(container).append(img);
        $(imgsContainer).append(container);
    }

    return imgsContainer;
}

//por cada post permitimos que puedan interactuar con el mismo
export function showInteraction(linkLike,linkComment,linkVisualization){
    let interactionContainer = $("<div></div>");
    $(interactionContainer).addClass("interaction");
    let protocol = window.location.protocol + "//"

    let interactions = `<div class="comments_container interaction_container">
            <a class="interaction_icon_container" href=${linkComment}>
                <i class="fa-regular fa-comment interaction_icon"></i>
            </a>
            <div class="count_interaction_container">
                <p class="comments_count count_interaction"></p>
            </div>
        </div>
    <div class="like_container interaction_container" id="${linkLike}">
        <div class="heart_bg">
            <div class="heart_icon">

            </div>
        </div>
        <div class="count_interaction_container">
            <p class="likes_count count_interaction"></p>
        </div>
    </div>
    <div class="visualizations_container interaction_container" id=${linkVisualization}>
        <div class="interaction_icon_container">
            <i class="fa-solid fa-chart-simple interaction_icon"></i>
        </div>
        <div class="count_interaction_container">
            <p class="visualizations_count count_interaction"></p>
        </div>
    </div>`

    $(interactionContainer).append(interactions);

    return interactionContainer;
}

{/* <div class="repost_container interaction_container">
<div class="interaction_icon_container">
    <i class="fa-solid fa-repeat interaction_icon"></i>
</div>
<div class="count_interaction_container">
    <p class="visualizations_count count_interaction"></p>
</div>
</div> */}

//verificamos si ya se ha interactuado con cierto contenido
export function postYetInteraction(interactionContainer,data){
    //interamos sobre todos los hijos del interactionContainer
    $.each(interactionContainer.children(), function (indexInArray, valueOfElement) { 
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

//marcamos  el icono de color en caso de que se haya interactuado
function updateInteracction(data,element){
    if(data && data.length > 0){
        let child = $(element).children()[0];
        let icon = $(child).children()[0]
        
        $(icon).css("color", "rgb(57, 179, 255)");
    }
}

//verificamos si ya se ha likeado el post ya agregamos la animacion correspondiente
export function postYetLiked(likeContainer,info){
    //obtenemos el icono del corazon
    let heartContainer = $(likeContainer).children(".heart_bg");
    let heart = $(heartContainer).children(".heart_icon")
    if(info.length >= 1){
        $(heart).css("animation","like-anim 0.5s steps(28) forwards");
    }
}

 //dependiendo de cada interaccion posible mostramos la candidad de likes, visualizaciones, comentarios etc
export function countIcon(data,interactionContainer){
    for(let i in data){
        if(i.includes("count")){
            let container = interactionContainer.find(`.${i}`);
            $(container).text(data[i]);
        }
    }
}

//likeamos el post
export async function likePost(likeContainer,likesCount,containerAddError){
    let heartContainer = $(likeContainer).children(".heart_bg");
    let hearstIcon = $(heartContainer).children(".heart_icon")

    $.each(hearstIcon, function (indexInArray, valueOfElement) {
         $(valueOfElement).on("click", async function (e) {
            e.preventDefault();
            let padre = $(e.target).closest(".like_container");
            let action = $(padre).attr("id");
            let response = await sendLike(action,containerAddError);
            if(response){
                $(e.target).css("animation","like-anim 0.5s steps(28) forwards");
                sumValue(likesCount)
            }
            else if(response == false){
                $(e.target).removeAttr("style");
                subtractValue(likesCount)
            }
         });
    });
}

//sumamos el valor
function sumValue(name){
    $(name).text(parseInt($(name).text())+ 1);
}

//restamos el valor
function subtractValue(name){
    $(name).text(parseInt($(name).text())- 1);
}

//enviamos el like
async function sendLike(action,container){
    try {
        const response = await $.ajax({
            type: "POST",
            url: action
        });
        return response
    } catch (error) {
        createErrorAlert(error.responseJSON.errors,container)
    }
}

//mostramos la cantidad de visualizaciones y likes
export function showRepostAndLikes(likesCount,visualizatiosCount){
    let interactionContainer = $(".repost_and_likes_container");

    let repostContainer = $("<div></div>");

    $(repostContainer).addClass("respost_count_container");

    let countRepost = $("<b></b>")
    $(countRepost).text(visualizatiosCount);

    let respostText = $("<p></p");
    $(respostText).text("Republicaciones");

    let likeContainer = $("<div></div>");
    $(likeContainer).addClass("likes_count_container");

    let countLikes = $("<b></b>");
    $(countLikes).text(likesCount);

    let likesText = $("<p></p>");
    $(likesText).text("Me gusta");


    $(repostContainer).append(countRepost);
    $(repostContainer).append(respostText);

    $(likeContainer).append(countLikes);
    $(likeContainer).append(likesText);

    $(interactionContainer).append(repostContainer);
    $(interactionContainer).append(likeContainer);
}

export function showVisualizations(visualizationsCount){
    let informationContainer = $(".post_information");

    let visualizationCountContainer = $("<div></div>");
    $(visualizationCountContainer).addClass("visualization_count_container");

    let count = $("<b></b>");

    $(count).text(visualizationsCount);

    let visualizationText = $("<p></p>");

    $(visualizationText).text("Visualizaciones");


    $(visualizationCountContainer).append(count);
    $(visualizationCountContainer).append(visualizationText);
    $(informationContainer).append(visualizationCountContainer);
}

export function savePost(saveFormContainer,saveCount,containerError){
    let saveContainer = $(saveFormContainer).children(".save_bg");
    let iconSave = $(saveContainer).children(".save_icon")

    $(saveFormContainer).on("click", async function (e) {
        e.preventDefault();
        let action = $(saveFormContainer).attr("id");
        let response = await sendSavePost(action,containerError);

        if(response){
            iconSave.css("animation","save-anim 0.5s steps(20) forwards")
            sumValue(saveCount)
        }
        else if(response == false){
            $(iconSave).removeAttr("style");
            subtractValue(saveCount);
        }
    });
}


async function sendSavePost(action,containerError){
    try {
        const response = await $.ajax({
            type: "POST",
            url: action
        });
        return response
    } catch (error) {
        createErrorAlert(error.responseJSON.errors,containerError);
    }
}

export function postYetSave(saveForm,info){
        //obtenemos el icono del corazon
        let saveContainer = $(saveForm).children(".save_bg");
        let saveIcon = $(saveContainer).children(".save_icon")
        if(info.length >= 1){
            $(saveIcon).css("animation","save-anim 0.5s steps(20) forwards");
        }
}
