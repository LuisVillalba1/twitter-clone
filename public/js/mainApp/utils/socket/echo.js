const linkProfileContainer = $(".logo_icon_container");


function getNickname(){
    let linkProfile = $(linkProfileContainer).attr("href");

    let username = linkProfile.split("/");
    return username[username.length - 1];
}

Echo.channel(`notification.${getNickname()}`).
listen("notificationEvent",(e)=>{
    console.log(e)
})
