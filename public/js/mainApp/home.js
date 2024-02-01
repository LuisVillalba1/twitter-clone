const nav = $(".nav_responsive");
const ownerLogoContainer = $(".owner_logo_container");

const header = $("header");
const mainContainer = $(".main_container");
const footer = $("footer");


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

        postYetLiked(likeContainer,currentPost.liked);
        likePost(likeContainer);
    }
    createIntersectionObserver();
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
    if(info){
        $(heart).css("animation","like-anim 0.5s steps(28) forwards");
    }
}

//remplazamos los espacios por un signo bajo
function deleteSpaces(value){
    return value.replace(/ /g, "_");
}

function chekIntersection(e){
    let data = e[0]
    let post = data.target;
    let id = $(post).attr("id");

    if(data.isIntersecting){
        console.log(post);
    }
    // console.log(`el elemento con id:${id} su intersepcion es:${data.isIntersecting}`)
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
        $(message).text(userMessage);
    
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

    let interactions = `<div class="comment_container">
            <i class="fa-regular fa-comment interaction_icon"></i>
        </div>
    <div class="repost_container">
        <i class="fa-solid fa-repeat interaction_icon"></i>
    </div>
    <form class="like_container" method="POST" action = "${actionLike}">
        <div class="heart_bg">
            <div class="heart_icon">

            </div>
        </div>
        <div class="likes_count"></div>
    </form>
    <div class="views_container">
        <i class="fa-solid fa-chart-simple interaction_icon"></i>
    </div>`

    $(interactionContainer).append(interactions);

    return interactionContainer;
}
