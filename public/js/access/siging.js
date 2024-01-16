
const contenedor_main = $(".main_container");

const label_container = $(".label_container");
const input_container = $("#input_container");
const inputSession = $("#init_session_input");

$(contenedor_main).on("click", function (e) {
    if(e.target !== $("#init_session_input")[0]){
        $(label_container).css("display","none");
        $(input_container).removeAttr("class")
        $(input_container).addClass("input_container");
        $(inputSession).attr("placeholder","Teléfono,correo electrónico o nombre de usuario");
    }
});

$(input_container).on("click", function (e) {
    $(label_container).attr("style", "display:block");
    $(label_container).children().css("color","rgb(29, 155, 240)");
    $(this).removeAttr("class");
    $(this).addClass("input_container_focus");
});

$(inputSession).on("focus", function (e) {
    $(e.target).removeAttr("placeholder");
});
