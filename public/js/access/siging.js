
const contenedor_main = $(".main_container");

const label_container = $(".label_container");
const input_container = $("#input_container");
const inputSession = $("#init_session_input");

const inputs = $("input");


//recorremos todos los inputs
$.each(inputs, function (indexInArray, valueOfElement) { 
    if(indexInArray != 0){
        $(valueOfElement).on("focus",function(e){
            //obtenemos el contenedor padre y su respectivo label container
            let padre = e.target.closest(".input_container");

            let label = padre.firstElementChild;

            //mostramos el label correspondiente y le agregamos estilos al contenedor
            if(!document.startViewTransition){

                $(e.target).addClass("input_focus");

                showLabel(label);
                $(padre).css("border-color", "rgb(29, 155, 240)");

                return ;
            }
            document.startViewTransition(()=>{
                $(e.target).addClass("input_focus");

                showLabel(label);
                $(padre).css("border-color", "rgb(29, 155, 240)");
            })
            
        });
        //ocultamos el label y le quitamos los estilos al contenedor
        $(valueOfElement).on("blur",function(e){
            let padre = e.target.closest(".input_container");
            let label = padre.firstElementChild;

            $(e.target).removeClass("input_focus");
            showLabel(label);
            $(padre).css("border-color", "rgb(156, 152, 152)");
        })
    }
});

//mostramos u ocultamos los label
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

document.addEventListener("keydown",(e)=>{
    if(e.key == "Enter"){
        sendFom();
    }
})

function sendFom(){
    let formData = $(".form_init_session").serialize();

    $.ajax({
        type: "post",
        url: $(".form_init_session").attr("action"),
        data : formData,
        success: function (response) {
            showMainApp(response);
        },
        error: function(error){
            $(".server_error").text("");
            $(".error_form").text("");
            if(error.status == 422){
                let errores = error.responseJSON.errors;
                $.each(errores, function (indexInArray, valueOfElement) {
                    $(`.error_${indexInArray}`).text(valueOfElement);
                });
            }
            else{
                let errores = error.responseJSON.error;
                $(".server_error").text(errores);
            }
        }
    });
}

//enviamos el formulario
$(".input_send_container").on("click", function (e) {
    sendFom();
});

//con la url obteneida navegamos hacia el pago numero 3
function showMainApp(url){
    if(!document.startViewTransition){
        window.location.href = url;
    }

    $(".register_container").attr("style","view-transition-name: mainbox");
    document.startViewTransition(async()=> await navigate(url));
}

function navigate(url){
    return new Promise(resolve=>{
        window.location.href = url
        resolve();
    })
}