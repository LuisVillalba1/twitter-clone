
const loaderContainer = $(".loader_container");

//obtenemos la informacion del post
async function getPostData(){
    let link = window.location.href + "/details";
    try{
    //mientras se obtiene la informacion mostramos el loader
    $(loaderContainer).css("display", "flex");
    let data = await $.ajax({
        type : "GET",
        url : link
    })
    //mostramos la informacion
    console.log(data);
    showData(data)
    }
    catch(e){
        console.log(e);
    }
    //una ves finalizada la peticion ocultamos el loader
    $(loaderContainer).css("display", "none");
}

getPostData();

function showData(data){
    //mostramos datos del usuario y el mensaje del post
    let userName = data.user.personal_data.Nickname;
    let logo = userName[0].toUpperCase();
    $(".logo").text(logo);

    $(".user_nickname").text(userName);

    let message = data.Message;

    if(message.length > 0){
        $(".user_message").text(message);
    }
    let multimedia = data.multimedia_post;

    if(multimedia.length > 0){
        showMultimedia(multimedia)
    }

    let interactions = data.interaction;
    createInteraction(interactions);

    let comments = data.interaction.comments
    showCommenst(comments)
}

//mostramos el contido multimedia en caso de que exista
function showMultimedia(data){
    for(let i in data){
        let currentMultimedia = data[i];

        let imgContainer = $("<div></div>");
        $(imgContainer).addClass("multimedia_img_container");

        let img = $("<img></img>");

        $(img).attr("alt",currentMultimedia.Name);
        $(img).attr("src", currentMultimedia.Url);

        $(imgContainer).append(img);

        $(".user_multimedia_container").append(imgContainer);
    }
}

function showCommenst(comments){
    if(!comments.length <= 0){
        let commentContainer = $("<div></div>");
        $(commentContainer).addClass("comment_post_constainer");

        let 
    }
}

//creamos las interacciones del post
function createInteraction(interactions){

    let interactionContainer = $(".interaction_post_container");
    let interaction = `<div class="interaction">
    <div class="comments_container interaction_container">
    <div class="interaction_icon_container">
        <i class="fa-regular fa-comment interaction_icon"></i>
    </div>
    <div class="count_interaction_container">
        <p class="comments_count count_interaction">${interactions.comments_count}</p>
    </div>
</div>
<div class="repost_container interaction_container">
    <div class="interaction_icon_container">
        <i class="fa-solid fa-repeat interaction_icon"></i>
    </div>
    <div class="count_interaction_container">
        <p class="visualizations_count count_interaction">${interactions.comments_count}</p>
    </div>
</div>
<form class="like_container interaction_container" method="POST">
    <div class="heart_bg">
        <div class="heart_icon">

        </div>
    </div>
    <div class="count_interaction_container">
        <p class="likes_count count_interaction">${interactions.likes_count}</p>
    </div>
</form>
<div class="visualizations_container interaction_container">
    <div class="interaction_icon_container">
        <i class="fa-solid fa-chart-simple interaction_icon"></i>
    </div>
    <div class="count_interaction_container">
        <p class="visualizations_count count_interaction">${interactions.visualizations_count}</p>
    </div>
</div>
</div>
    `

$(interactionContainer).append(interaction);
}