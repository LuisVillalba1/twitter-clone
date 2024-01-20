//tomamos todos los inputs
const inputs = $("input");

//recorremos todos los inputs
$.each(inputs, function (indexInArray, valueOfElement) { 
    if(indexInArray != 0){
        $(valueOfElement).on("focus",function(e){
            //obtenemos el contenedor padre y su respectivo label container
            let padre = e.target.closest(".input_container");

            let label = padre.firstElementChild;

            //mostramos label y agregamos un borde a el contenedor
            if(!document.startViewTransition){
                showLabel(label);
                $(padre).css("border-color", "rgb(29, 155, 240)");
                $(e.target).addClass("input_focus");
                return ;
            }
            document.startViewTransition(()=>{
                showLabel(label);
                $(padre).css("border-color", "rgb(29, 155, 240)");
                $(e.target).addClass("input_focus");
            })
            
        });
        //en caso de que el input ya no este mas focus modificamos el borde del contenedor
        //y ocultamos el label
        $(valueOfElement).on("blur",function(e){
            let padre = e.target.closest(".input_container");
            let label = padre.firstElementChild;

            showLabel(label);
            $(padre).css("border-color", "rgb(156, 152, 152)");
            $(e.target).removeClass("input_focus");
        })
    }
});

//mostramos u ocultamos el label
function showLabel(label){
    if($(label).attr("class") == "label_container"){
        $(label).removeClass("label_container");
        $(label).addClass("label_container_show");
        let hijo = label.firstElementChild;
        $(hijo).addClass("label_show");
    }
    else{
        $(label).removeClass("label_container_show");
        $(label).addClass("label_container");
    }
}

function showLoader(){
    if(!document.startViewTransition){
        $(".loader_container").css("display", "flex");
        return;
    }

    document.startViewTransition(()=>{
        $(".loader_container").css("display", "flex");
    })
}

function ocultContainer(){
    $(".title_recuperate_account").css("display","none");
    $(".change_password_form").css("display","none");
    $(".continue_container").css("display","none");
}

function ocultLoader(){
    $(".loader_container").css("display", "none");
}

function showContainer(){
    $(".title_recuperate_account").css("display","block");
    $(".change_password_form").css("display","flex");
    $(".continue_container").css("display","flex")
}

function showSucefullyReponse(value){
    let container = $(".succefully_response_container");
    $(container).css("display", "flex");

    let hijo = container[0].firstElementChild;

    $(hijo).addClass("succefully_response");
    $(hijo).text(value);
}

$(".continue_botton").on("click", function (e) {

    let formData = $(".change_password_form").serialize();

    $(".error_form").text("");
    $(".error_server").text("");
    ocultContainer();
    showLoader();
    $.ajax({
        type: "post",
        url: $(".change_password_form").attr("action"),
        data : formData,
        success: function (response) {
            ocultLoader();
            showSucefullyReponse(response);
        },
        error: function(error){
            console.log(error);
            ocultLoader();
            showContainer();
            if(error.status == 422){
                let errores = error.responseJSON.errors;
                $.each(errores, function (indexInArray, valueOfElement) {
                    $(`.error_${indexInArray}`).text(valueOfElement);
                });
            }
            else{
                let errores = error.responseJSON.error;
                $(".error_server").text(errores);
            }
        }
    });
});