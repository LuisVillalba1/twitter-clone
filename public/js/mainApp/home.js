import * as utilsPosts from "./utils/utilsPosts.js";
import * as utilsIntersection from "./utils/utilsIntersection.js";
import {createErrorAlert} from "./utils/error/errorAlert.js";
import { createLoader } from "./utils/createLoader.js";


const allPost = $(".posts_container");
const urlLocation = window.location.href + "/getPosts";

//obtenemos todas las publicaciones
function getPublicPosts(pageUrl){
    let url = "";
    if(pageUrl == 0){
        url = urlLocation;
    }
    else{
        url = pageUrl;
    }
    //mostramos un el loader
    //obtenemos las publicaciones aun no vistas por el usuario
    $.ajax({
        type: "get",
        url: url,
        success: function (response) {
            if(response.data && response.data.length > 0){
                let nextPageUrl = response.next_page_url;
                showPosts(response.data,allPost,getPublicPosts.bind(null,nextPageUrl))
            }else{
                showNoMorePostAlert();
            }
            $(".loader_container").remove();
        },
        error: function(e){
            createErrorAlert(e.responseJSON.error,allPost);
            $(".loader_container").remove();
        }
    });
}

getPublicPosts(0);


//mostramos los posteos no visualizados
function showPosts(posts,container,callBack){
    createPostContent(posts,container)
    utilsIntersection.createIntersectionObserver(".post",true,false,callBack)
}

//iteramos sobre cada posteo y creamos los contenedores
function createPostContent(posts,container){
    posts.forEach(currentPost=>{
        createContainers(currentPost,container);
    })
}
//creamos los contenedores
function createContainers(currentPost,mainContainer){
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

    //Obtenemos los links de las imagenes del usuario y el link del perfil
    let userImg = currentPost.user.profile.ProfilePhotoURL;
    let userImgName = currentPost.user.profile.ProfilePhotoName;

    let linkProfile = currentPost.linkProfile;
    
    //mostramos el logo del usuario
    $(userDataContainer).append(utilsPosts.logoContainerShow(nickname,userImg,userImgName));
    $(postContainer).append(userDataContainer);
    //mostramos el mensaje del usuario en caso de que exista
    let postContent = utilsPosts.showNameAndMessage(nickname,message,linkProfile);

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
    $(mainContainer).append(postContainer);

    //Obtenemos el contenedor del like y la suma de likes que contiene el mismo
    let likeContainer = $(interactionContainer).children(".like_container");
    let likesCount = $(likeContainer).children().children(".likes_count")

    utilsPosts.postYetInteraction(interactionContainer,currentPost)
    utilsPosts.postYetLiked(likeContainer,currentPost.likes);
    utilsPosts.likePost(likeContainer,likesCount,mainContainer);

    utilsPosts.countIcon(currentPost,interactionContainer);
}

function showNoMorePostAlert(){
    let container = $("<div></div>");
    $(container).addClass("no_more_posts_container");
    let content = `<div class="check_no_posts_container">
    <div class="check_icon_container">
        <img src="./imgs/check.png" alt="">
    </div>
</div>
<h3>Estás al día</h3>
<p class="see_more_publics">Ver publicaciones anteriores</p>`

$(container).append(content);
$(allPost).append(container);

getPostsVisualized(window.location.href + "/getPosts/visualizated");
}

//obtenemos los posteos no visualizados
function getPostsVisualized(url){
    $(".see_more_publics").on("click", async function () {
        fetchPublicsVisualized(url)
    });
}

async function fetchPublicsVisualized(url){
    $(".no_more_posts_container").css("display","none")
    $(allPost).append(createLoader());
    try{
        const response = await $.ajax({
            type : "get",
            url : url
        })
        if(!response.data){
            return
        }
        showMorePosts(response.data,allPost,response.next_page_url)
    }
    catch(e){
        if(e.responseJSON){
            return createErrorAlert(e.responseJSON.errors,allPost)
        }
        createErrorAlert(e,allPost);
    }
    finally{
        $(".loader_container").remove();
    }
}

//mostramos los posteos no visualizados
function showMorePosts(posts,mainContainer,url){
    //iteramos sobra cada posteo y luego solicitamos mas
    posts.forEach(currentPost => {
        let postData = currentPost.post_interaction;
        createContainers(postData,mainContainer);
        
    });
    utilsIntersection.createIntersectionObserver(".post",false,false,fetchPublicsVisualized.bind(null,url))
}
