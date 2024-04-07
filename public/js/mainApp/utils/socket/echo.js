

const linkProfileContainer = $(".logo_icon_container");


//seteamos la cantidad actual de notificaciones
function setCurrentNotifications(){
    //obtenemos los contenedores
    if(localStorage.getItem("notificationCount")){
        //obtenemos los contenedores
        $(".count_notification_container").css("display", "block");
        let count = $(".count_notification");
        $.each(count, function (indexInArray, valueOfElement) { 
            $(valueOfElement).text(localStorage.getItem("notificationCount"));
        });
    }
}

setCurrentNotifications();

function getNickname(){
    let linkProfile = $(linkProfileContainer).attr("href");

    let username = linkProfile.split("/");
    return username[username.length - 1];
}

Echo.channel(`notification.${getNickname()}`).
listen("notificationEvent",(e)=>{
    sumNotificationCount();
})

//cada ves que recibimos vamos a mostrar un icono con la cantidad de notificiaciones recibidas
function sumNotificationCount(){
    //obtenemos los contenedores
    $(".count_notification_container").css("display", "block");
    let count = $(".count_notification");

    //obtenemos la cantidad de notificaciones
    let notificationCount = getCountNotifications();
    //por cada contenedor le agregamos la cantidad de notificaciones correspondientes
    $.each(count, function (indexInArray, valueOfElement) { 
        $(valueOfElement).text(notificationCount);
    });
}

function getCountNotifications(){
    //obtenemos la cantidad de notificaciones
    let notificationCountValue = parseInt(localStorage.getItem("notificationCount"));
    //en caso de que sea una gran cantidad de notificaciones devolvemos un +99
    if(notificationCountValue >= 99 || NaN){
        return "+99"
    }
    if(!notificationCountValue){
        localStorage.setItem("notificationCount",1);
    }
    else{
        localStorage.setItem("notificationCount",notificationCountValue + 1);
    }
    return parseInt(localStorage.getItem("notificationCount"));
}


