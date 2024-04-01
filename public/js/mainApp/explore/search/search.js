
import * as utilsPosts from "../../utils/utilsPosts.js";
import * as utilsIntersection from "../../utils/utilsIntersection.js";
import {createFollow} from "../../profile/follow/utils/utilFollow.js";
import { showComment } from "./utils/utilsComments.js";
import { createLoader } from "../../utils/createLoader.js";

const mainContent = $(".search_content");
const mainContainer = $(".main_content");

//obtenemos el link visitado
function checkLinkVisited(){
    let query = window.location.search;

    if(query.includes("c=true")){
        addColorBottom(".comments_link")
    }
    else if(query.includes("u=true")){
        addColorBottom(".users_link")
    }
    else{
        addColorBottom(".post_link")
    }
}

function addColorBottom(element){
    $(element).addClass("link_visited");
}

checkLinkVisited();


function getData(page){
    $(mainContent).append(createLoader());
    let url = "";
    if(page == 0){
        let queryData = window.location.search;
        url = window.location.origin + "/searchData" + queryData
    }
    else{
        url = page;
    }

    $.ajax({
        type: "GET",
        url: url,
        success: function (response) {
            let type = response.type;
            let data = response.data;
            if(data && data.data.length > 0){
                let nextPage = data.next_page_url;
                showData(type,data.data,nextPage);
            }
            else if((data && data.data.length == 0 && page == 0)){
                createNoContent();
            }

            $(".loader_container").remove();
        },
        error : function (error){
            console.log(error);
        }
    });
}

function createNoContent(){
    const urlParams = new URLSearchParams(window.location.search);

    //Accedemos a los valores
    let query = urlParams.get('q');

    let noContentContainer = $("<div></div>");
    $(noContentContainer).addClass("no_content_container");

    let title = $("<h3></h3>");
    $(title).addClass("title_no_content");
    $(title).text(`No hay resultados para "${query}"`);

    let paragrapht = $("<p></p>");
    $(paragrapht).addClass("no_content_text");
    $(paragrapht).text("Intenta buscar otra cosa");

    $(noContentContainer).append(title);
    $(noContentContainer).append(paragrapht);
    $(mainContent).append(noContentContainer);
}

getData(0);

function showData(type,data,nextPage){
    if(type == "posts"){
        //mostramos los posteos
        showPosts(data,mainContent,getData.bind(null,nextPage));
    }
    if(type == "profiles"){
        for(let i of data){
            createFollow(i,mainContent,mainContainer)
        }
        getData.bind(null,nextPage);
    }
    if(type == "comments"){
        for(let i of data){
            showComment(i,mainContent,mainContainer)
        }
        utilsIntersection.createIntersectionObserver(".current_post",true,null,getData.bind(null,nextPage))
    }
}

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