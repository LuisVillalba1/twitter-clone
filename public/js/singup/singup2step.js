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
                return ;
            }
            document.startViewTransition(()=>{
                showLabel(label);
                $(padre).css("border-color", "rgb(29, 155, 240)");
            })
            
        });
        //en caso de que el input ya no este mas focus modificamos el borde del contenedor
        //y ocultamos el label
        $(valueOfElement).on("blur",function(e){
            let padre = e.target.closest(".input_container");
            let label = padre.firstElementChild;

            showLabel(label);
            $(padre).css("border-color", "rgb(156, 152, 152)");
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

//enviamos el formulario
$(".continue_botton").on("click", function (e) {

    let formData = $(".register_data").serialize();

    $.ajax({
        type: "post",
        url: $(".register_data").attr("action"),
        data : formData,
        success: function (response) {
            showStep3(response);
        },
        error: function(error){
            $(".server_erro").text("");
            $(".error_form").text("");
            if(error.status == 422){
                let errores = error.responseJSON.errors;
                $.each(errores, function (indexInArray, valueOfElement) {
                    $(`.error_${indexInArray}`).text(valueOfElement);
                });
            }
            $(".server_error").text(error.responseJSON.error);
        }
    });
});
//con la url obteneida navegamos hacia el pago numero 3
function showStep3(url){
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