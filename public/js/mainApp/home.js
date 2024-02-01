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
                console.log(response);
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
        $(postContent).append(showInteraction(nicknameNoSpaces,postID));
        $(postContainer).append(postContent);
        //agregamos todos los post al contenedor principal
        $(allPost).append(postContainer);
    }
    likePost();
}

//remplazamos los espacios por un signo bajo
function deleteSpaces(value){
    return value.replace(/ /g, "_");
}

//permitimos que el usuario pueda likear cada post
function likePost(){
    let hearstIcon = $(".heart_icon");
    let likesContainer = $(".like_container");

    $.each(hearstIcon, function (indexInArray, valueOfElement) { 
         $(valueOfElement).on("click", function (e) {
            if($(e.target).attr("style")){
                $(e.target).removeAttr("style");
            }
            else{
                $(e.target).css("animation","like-anim 0.5s steps(28) forwards");
            }
            let padre = $(e.target).closest(".like_container");
            let action = $(padre).attr("action");
            sendLike(action)
         });
    });
}

function sendLike(action){
    $.ajax({
        type: "POST",
        url: action,
        success: function (response) {
            console.log(response)
        },
        error:function(e){
            console.log(e.responseJSON.message);
        }
    });
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
