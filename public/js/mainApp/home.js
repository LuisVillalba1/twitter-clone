import * as utilsPosts from "./utils/utilsPosts.js";
import * as utilsIntersection from "./utils/utilsIntersection.js";


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
        console.log(valueOfElement);
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
            console.log("end");
            return
        }
        //iteramoc sobre cada post
        posts.forEach(currentPost=>{
            //obtenemos todos los links para las interacciones y mostrar el post
            let linkPost = currentPost.linkPost;
            let linkLike = currentPost.linkLike;
            let linkVisualization = currentPost.linkVisualization;
            let linkComment = currentPost.linkComment;
        
            //a cada post le agregamos un id con su nickname y el numero de post
            let postContainer = $("<a></a>");
            $(postContainer).addClass("post");
            $(postContainer).attr("href", linkPost);
        
            let nickname = currentPost.user.personal_data.Nickname;
            let postID = currentPost.PostID;
        
            let nicknameNoSpaces = utilsPosts.deleteSpaces(nickname);
            $(postContainer).attr("id",`${nicknameNoSpaces}-${postID}`);
        
            let message = currentPost.Message;
        
            let userDataContainer = $("<div></div>");
            $(userDataContainer).addClass("user_data_container");
        
            //mostramos el logo del usuario
            $(userDataContainer).append(utilsPosts.logoContainerShow(nickname));
            $(postContainer).append(userDataContainer);
            //mostramos el mensaje del usuario en caso de que exista
            let postContent = utilsPosts.showNameAndMessage(nickname,message);
        
            $(userDataContainer).append(postContent);
        
        
            let multimedia = currentPost.multimedia_post;
            if(multimedia && multimedia.length > 0){
                //mostramos el contenido multimedia
                $(postContent).append(utilsPosts.showMultimedia(multimedia));
            }
            //obtenemos las interacciones y lo agregamos al post content
            let interactionContainer = utilsPosts.showInteraction(linkLike,linkComment,linkVisualization);
        
            $(postContainer).append(userDataContainer);
            //agregamos al contenedor del post el post container
            $(postContainer).append(interactionContainer);
        
            //agregamos todos los post al contenedor principal
            $(allPost).append(postContainer);
        
            //Obtenemos el contenedor del like y la suma de likes que contiene el mismo
            let likeContainer = $(interactionContainer).children(".like_container");
            let likesCount = $(likeContainer).children().children(".likes_count")
        
            utilsPosts.postYetInteraction(interactionContainer,currentPost)
            utilsPosts.postYetLiked(likeContainer,currentPost.likes);
            utilsPosts.likePost(likeContainer,likesCount);
        
            utilsPosts.countIcon(currentPost,interactionContainer);
        })
        //aumentamos el indice luego de mostrar los posts
        currentIndex += postPerPage
        //verificamos si el ultimo post mostrado es interceptado
        //utilizamos bind para poder llamar a esta funcion callback dentro de createIntersectionObserver con los argumentos necesarios
        utilsIntersection.createIntersectionObserver(".post",true,showMorePosts.bind(null,currentIndex,postPerPage))
    }
    showMorePosts(currentIndex,postPerPage)
}



