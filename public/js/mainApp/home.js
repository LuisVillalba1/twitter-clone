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

const heartIcon = $(".heart_icon");
const likeContainer = $(".like_container");

$(likeContainer).on("click", function () {
    if($(heartIcon).attr("style")){
        $(heartIcon).removeAttr("style");
    }
    else{
        $(heartIcon).css("animation","like-anim 0.5s steps(28) forwards");
    }
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

