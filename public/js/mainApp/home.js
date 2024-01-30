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


//show nav
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

function interactionIcon(){
    let posts = $(".post");

    let icons = posts.find(".interaction_icon");

    $(icons).on("click", function (e) {
        $(e.target).css("animation", "0.4s ease-in 0s none iconSelected");
    });
}

interactionIcon();


function getPublicPosts(){

    $.ajax({
        type: "get",
        url: $(".get_publics").attr("id"),
        success: function (response) {
            if(response.length > 0){
                showPosts(response)
                console.log(response);
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
        //main post
        let postContainer = $("<div></div>");
        $(postContainer).addClass("post");


        $(postContainer).append(logoContainerShow(currentPost.user.Name));
        let postContent = showNameAndMessage(currentPost.user.personal_data.Nickname,currentPost.Message)

        if(currentPost.multimedia_post && currentPost.multimedia_post.length > 0){
            $(postContent).append(showMultimedia(currentPost.multimedia_post));
        }
        $(postContent).append(showInteraction());
        $(postContainer).append(postContent);
        $(allPost).append(postContainer);
    }
    likePost();
}

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
         });
    });
}


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

function showNameAndMessage(username,userMessage){
    let postContent = $("<div></div>");
    $(postContent).addClass("post_content");
    

    let nameContainer = $("<div></div>");
    $(nameContainer).addClass("name_container");
    let name = $("<h5></h5>");
    $(name).text(username);

    $(nameContainer).append(name);

    let content = $("<div></div>");
    $(content).addClass("content");
    let message = $("<p></p>");
    $(message).text(userMessage);

    $(content).append(message);

    $(postContent).append(nameContainer);
    $(postContent).append(content);

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

function showInteraction(){
    let interactionContainer = $("<div></div>");
    $(interactionContainer).addClass("interaction");

    let interactions = `<div class="comment_container">
            <i class="fa-regular fa-comment interaction_icon"></i>
        </div>
    <div class="repost_container">
        <i class="fa-solid fa-repeat interaction_icon"></i>
    </div>
    <div class="like_container">
        <div class="heart_bg">
            <div class="heart_icon">

            </div>
        </div>
        <div class="likes_count"></div>
    </div>
    <div class="views_container">
        <i class="fa-solid fa-chart-simple interaction_icon"></i>
    </div>`

    $(interactionContainer).append(interactions);

    return interactionContainer;
}


