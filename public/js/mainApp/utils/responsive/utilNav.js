const nav = $(".nav_responsive");
const ownerLogoContainer = $(".owner_logo_container");

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
