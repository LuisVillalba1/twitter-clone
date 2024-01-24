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

function likeAndDislikePost(){
    let likeContainer = $(".like_container");

    let likeIcon = likeContainer.find(".fa-heart");

    $(likeIcon).on("click", function (e) {
        let padre = e.target.closest(".post");

        if($(padre).hasClass("liked")){
            $(e.target).removeAttr("style");
            $(padre).removeClass("liked");
            return ;
        }

        $(e.target).css("animation","0.3s ease-in 0s forwards 1 likePost");
        $(padre).addClass("liked");
    });
}

likeAndDislikePost();


function interactionIcon(){
    let posts = $(".post");

    let icons = posts.find(".interaction_icon");

    $(icons).on("click", function (e) {
        $(e.target).css("animation", "0.4s ease-in 0s none iconSelected");
    });
}

interactionIcon();

const textareaPost = $("#textarea_post");

$(textareaPost).on("input", function (e) {
    $(e.target).css("height", "50px");
    $(e.target).css("height", e.target.scrollHeight + 'px');
});


const iconUploadImg = $(".icon_upload_img");
const inputUploadImg = $("#upload_img_input");

$(iconUploadImg).on("click", function () {
    inputUploadImg.click();
});

