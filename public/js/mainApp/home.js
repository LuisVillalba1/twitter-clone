const nav = $(".nav_responsive");
const ownerLogoContainer = $(".owner_logo_container");

const header = $("header");
const mainContainer = $(".main_container");
const footer = $("footer");


const host = window.location.protocol + "//" + window.location.host

function filterElements(){
    let hijosBody = $("body").children().slice(0,-2);

    $.each(hijosBody, function (indexInArray, valueOfElement) { 
         if(!$(valueOfElement).hasClass("nav_responsive")){
            $(valueOfElement).css("filter","blur(1px)");
         }
    });
}

function desfilterElements(){
    let hijosBody = $("body").children().slice(0,-2);

    $.each(hijosBody, function (indexInArray, valueOfElement) { 
        if(!$(valueOfElement).hasClass("nav_responsive")){
           $(valueOfElement).removeAttr("style");
        }
   });
}


//show nav y filtramos los elementos
$(ownerLogoContainer).on("click", function (e) {
    if(!document.startViewTransition){
        $(nav).addClass("nav_responsive_show");
        filterElements();
        return;
    }
    document.startViewTransition(()=>{
        $(nav).addClass("nav_responsive_show");
        filterElements();
    })
});

//ocult nav
$(document.body).on("click", function (e) {
    if($(nav).hasClass("nav_responsive_show") && e.target != nav[0] && !nav.has(e.target).length){
        if(!document.startViewTransition){
            $(nav).removeClass("nav_responsive_show");
            desfilterElements();
            return;
        }
        document.startViewTransition(()=>{
            $(nav).removeClass("nav_responsive_show");
            desfilterElements();
        });

    }
});

//obtenemos todas las publicaciones
function getPublicPosts(){

    $.ajax({
        type: "get",
        url: $(".get_publics").attr("id"),
        success: function (response) {
            if(response.length > 0){
                showPosts(response)
            }
        },
        error: function(e){
            console.log(e);
        }
    });
}

getPublicPosts();

const allPost = $(".posts_container");

function showPosts(info){
    for(let i in info){
        let currentPost = info[i]
        //a cada post le agregamos un id con su nickname y el numero de post
        let postContainer = $("<div></div>");
        $(postContainer).addClass("post");

        let nickname = currentPost.user_post.user.personal_data.Nickname;
        let postID = currentPost.user_post.PostID;

        let nicknameNoSpaces = deleteSpaces(nickname);
        $(postContainer).attr("id",`${nicknameNoSpaces}-${postID}`);


        let name = currentPost.user_post.user.Name;
        let message = currentPost.user_post.Message;
        $(postContainer).append(logoContainerShow(name));
        let postContent = showNameAndMessage(nickname,message);

        let multimedia = currentPost.user_post.multimedia_post;
        if(multimedia && multimedia.length > 0){
            $(postContent).append(showMultimedia(multimedia));
        }
        //obtenemos las interacciones y lo agregamos al post content
        let interactionContainer = showInteraction(nicknameNoSpaces,postID)

        $(postContent).append(interactionContainer);
        //agregamos al contenedor del post el post container
        $(postContainer).append(postContent);

        //agregamos todos los post al contenedor principal
        $(allPost).append(postContainer);

        //Obtenemos el contenedor del like
        let likeContainer = $(interactionContainer).children(".like_container");

        postYetInteraction(interactionContainer,currentPost)
        postYetLiked(likeContainer,currentPost.likes);
        likePost(likeContainer);

        countIcon(currentPost);
    }
    createIntersectionObserver();
}

//dependiendo de cada interaccion posible mostramos la candidad de likes, visualizaciones, comentarios etc
function countIcon(data){
    for(let i in data){
        if(i.includes("count")){
            let container = $(`.${i}`);
            $(container).text(data[i]);
        }
    }
}

function createIntersectionObserver(){
    //obtenemos todos los post
    let posts = document.querySelectorAll(".post");

    posts.forEach(post=>{
        let observer = new IntersectionObserver(chekIntersection,{});
        observer.observe(post)
    })
}

function postYetLiked(likeContainer,info){
    //obtenemos el icono del corazon
    let heartContainer = $(likeContainer).children(".heart_bg");
    let heart = $(heartContainer).children(".heart_icon")
    if(info.length >= 1){
        $(heart).css("animation","like-anim 0.5s steps(28) forwards");
    }
}

//verificamos si el post ya ha obtenido cierta interaccion
function postYetInteraction(interactionContainer,data){
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

//agregamos un color al icono
function updateInteracction(data,element){
    if(data && data.length > 0){
        let child = $(element).children()[0];
        let icon = $(child).children()[0]
        
        $(icon).css("color", "rgb(57, 179, 255)");
    }
}

//remplazamos los espacios por un signo bajo
function deleteSpaces(value){
    return value.replace(/ /g, "_");
}

function chekIntersection(e){
    //obtenemos el post que se ha visualidado
    let data = e[0]
    let post = data.target;
    let id = $(post).attr("id");

    //en caso de que el usuario lo haya visto obtenemos los datos del post y la enviamos al servidor
    if(data.isIntersecting){
        let data = getUserAndID(id)

        sendVisualization(data.usuario,data.id);
    }
}

function getUserAndID(cadena) {
    // Buscar la última aparición del guion "-"
    let ultimoGuionIndex = cadena.lastIndexOf('-');

    if (ultimoGuionIndex !== -1) {
        // Extraer el nombre de usuario y el ID
        let usuario = cadena.substring(0, ultimoGuionIndex);
        let id = cadena.substring(ultimoGuionIndex + 1);

        return { usuario, id };
    } else {
        // En caso de que no haya guion, asumimos que la cadena completa es el nombre de usuario
        return { usuario: cadena, id: null };
    }
}

//enviamos los datos al servidor
function sendVisualization(user,id){
    let url = window.location.protocol + "//" + window.location.host + `/visualization/${user}?post=${id}`;
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

//permitimos que el usuario pueda likear cada post
async function likePost(likeContainer){
    let heartContainer = $(likeContainer).children(".heart_bg");
    let hearstIcon = $(heartContainer).children(".heart_icon")

    $.each(hearstIcon, function (indexInArray, valueOfElement) {
         $(valueOfElement).on("click", async function (e) {
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

async function sendLike(action){
    try {
        const response = await $.ajax({
            type: "POST",
            url: action
        });
        return response;
    } catch (error) {
        console.log(error.responseJSON.message);
    }
}

//agregamos el logo del post el cual contendra la primera letra del usuario
function logoContainerShow(Name){
    let logoContainer = $("<div></div>");
    let imgContainer = $("<div></div>");
    $(logoContainer).addClass("logo_container");
    $(imgContainer).addClass("img_container");

    let firstLetter = $("<h4></h4>");
    $(firstLetter).text(Name[0].toUpperCase());

    $(imgContainer).append(firstLetter);
    $(logoContainer).append(imgContainer);

    return logoContainer;
}

//creamos los contenedores del nombre de usuario y mensaje
function showNameAndMessage(username,userMessage){
    let postContent = $("<div></div>");
    $(postContent).addClass("post_content");
    

    let nameContainer = $("<div></div>");
    $(nameContainer).addClass("name_container");
    let name = $("<h5></h5>");
    $(name).text(username);

    $(nameContainer).append(name);

    $(postContent).append(nameContainer);
    if(userMessage){
        let content = $("<div></div>");
        $(content).addClass("content");
        let message = $("<p></p>");
        message[0].textContent = userMessage;
    
        $(content).append(message);
        $(postContent).append(content);

        return postContent;
    }

    return postContent;
}

function showMultimedia(multimedia){
    let imgsContainer = $("<div></div>");
    $(imgsContainer).addClass("img_container_user_posts");
    for(let i in multimedia){
        let currenMultimedia = multimedia[i];
        let img = $("<img></img>");
        $(img).addClass("user_posts_img");
        $(img).attr("src", currenMultimedia.Url);
        $(img).attr("alt", currenMultimedia.Name);

        $(imgsContainer).append(img);
    }

    return imgsContainer;
}

//por cada post permitimos que puedan interactuar con el mismo
function showInteraction(nickname,post){
    let interactionContainer = $("<div></div>");
    $(interactionContainer).addClass("interaction");
    let protocol = window.location.protocol + "//"
    let actionLike = protocol + window.location.host + "/likePost/" + nickname + "?post=" + post

    let interactions = `<div class="comments_container interaction_container">
            <a class="interaction_icon_container" href= ${host + "/comment/"}${nickname + "?post="}${post} >
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
    <form class="like_container interaction_container" method="POST" action = "${actionLike}">
        <div class="heart_bg">
            <div class="heart_icon">

            </div>
        </div>
        <div class="count_interaction_container">
            <p class="likes_count count_interaction"></p>
        </div>
    </form>
    <div class="visualizations_container interaction_container">
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
