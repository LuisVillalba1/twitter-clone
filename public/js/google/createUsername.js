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
        //en caso de que el input ya no este mas focus modificamos el borde del contenedor
        //y ocultamos el label
        $(valueOfElement).on("blur",function(e){
            let padre = e.target.closest(".input_container");
            let label = padre.firstElementChild;

            $(e.target).removeClass("input_focus");
            showLabel(label);
            $(padre).css("border-color", "rgb(156, 152, 152)");
        })
    }
});



const mainContainer = $(".main_container");

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

function sendForm(formData){
    
    $.ajax({
        type: "post",
        url: $(".register_data").attr("action"),
        data : formData,
        success: function (response) {
            showStep2(response);
        },
        error: function(error){
            if(error.status == 422){
                let errores = error.responseJSON.errors;
                $(".error_form").text("");
                $.each(errores, function (indexInArray, valueOfElement) {
                    $(`.error_${indexInArray}`).text(valueOfElement);
                });
            }
            else{
                let errores = error.responseJSON.error;
                $.each(errores, function (indexInArray, valueOfElement) { 
                    $(".error_form").text(valueOfElement); 
                });
            }
        }
    });
}

$("body").on("keydown", function (e) {
    if(e.key == "Enter"){
        let formData = $(".register_data").serialize();
        sendForm(formData)
    }
});

$(".continue_botton").on("click", function (e) {

    let formData = $(".register_data").serialize();

    sendForm(formData)
});


function showStep2(url){
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