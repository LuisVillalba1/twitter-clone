$(".create_account").on("click",function(e){
    e.preventDefault();

    if(!document.startViewTransition){
        navigate($(".create_account").attr("href"))
        return;
    }
    $(".main_container").attr("style","view-transition-name: mainbox");
    document.startViewTransition(async()=>await navigate($(".create_account").attr("href")));
})

$(".init_session").on("click", function (e) {

    e.preventDefault();

    if(!document.startViewTransition){
        navigate($(".init_session").attr("href"))
        return;
    }

    $(".main_container").attr("style","view-transition-name: mainbox");
    document.startViewTransition(async ()=>await navigate($(".init_session").attr("href")))
})

async function navigate(url) {
    return new Promise(resolve => {
        window.location.href = url;
        resolve();
    });
  }
