//eliminamos los espacios de un string
export function deleteSpaces(value){
    return value.replace(/ /g, "_");
}

//añadimos la imagen del usuario, por ahora solo sera la primera letra en mayuscula del nickname
export function logoContainerShow(Name){
    let logoContainer = $("<div></div>");
    let imgContainer = $("<div></div>");
    $(logoContainer).addClass("owner_logo_container");
    $(imgContainer).addClass("owner_logo");

    let firstLetter = $("<h4></h4>");
    $(firstLetter).text(Name[0].toUpperCase());

    $(imgContainer).append(firstLetter);
    $(logoContainer).append(imgContainer);

    return logoContainer;
}

//creamos los contenedores del nombre de usuario y mensaje
export function showNameAndMessage(username,userMessage){
    let postContent = $("<div></div>");
    $(postContent).addClass("content");
    

    let nameContainer = $("<div></div>");
    $(nameContainer).addClass("user_nickname_container");
    let name = $("<h5></h5>");
    $(name).addClass("user_nickname");
    $(name).text(username);

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
export function showInteraction(nickname,post,linkLike,linkComment,linkVisualization){
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
    <div class="repost_container interaction_container">
        <div class="interaction_icon_container">
            <i class="fa-solid fa-repeat interaction_icon"></i>
        </div>
        <div class="count_interaction_container">
            <p class="visualizations_count count_interaction"></p>
        </div>
    </div>
    <form class="like_container interaction_container" method="POST" action ="${linkLike}">
        <div class="heart_bg">
            <div class="heart_icon">

            </div>
        </div>
        <div class="count_interaction_container">
            <p class="likes_count count_interaction"></p>
        </div>
    </form>
    <form class="visualizations_container interaction_container" method="POST" action=${linkVisualization}>
        <div class="interaction_icon_container">
            <i class="fa-solid fa-chart-simple interaction_icon"></i>
        </div>
        <div class="count_interaction_container">
            <p class="visualizations_count count_interaction"></p>
        </div>
    </form>`

    $(interactionContainer).append(interactions);

    return interactionContainer;
}

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
export function countIcon(data){
    for(let i in data){
        if(i.includes("count")){
            let container = $(`.${i}`);
            $(container).text(data[i]);
        }
    }
}

//likeamos el post
export async function likePost(likeContainer){
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

//enviamos el like
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

export function sendVisualization(url){
    $.ajax({
        type: "POST",
        url: url,
        success: function (response) {
            // console.log(response);
        },
        error : function(error){
            console.log(error)
        }
    });
}
