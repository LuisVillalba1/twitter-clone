
//tomamos todos los inputs
const inputs = $("input");

//recorremos todos los inputs
$.each(inputs, function (indexInArray, valueOfElement) { 
    if(indexInArray != 0 && indexInArray!= 3){
        $(valueOfElement).on("focus",function(e){
            //obtenemos el contenedor padre y su respectivo label container
            let padre = e.target.closest(".input_container");

            let label = padre.firstElementChild;

            //mostramos el label correspondiente y le agregamos estilos al contenedor
            if(!document.startViewTransition){

                $(e.target).addClass("input_contact_focus");

                showLabel(label);
                $(padre).css("border-color", "rgb(29, 155, 240)");

                return ;
            }
            document.startViewTransition(()=>{
                $(e.target).addClass("input_contact_focus");

                showLabel(label);
                $(padre).css("border-color", "rgb(29, 155, 240)");
            })
            
        });
        //ocultamos el label y le quitamos los estilos al contenedor
        $(valueOfElement).on("blur",function(e){
            let padre = e.target.closest(".input_container");
            let label = padre.firstElementChild;

            $(e.target).removeClass("input_contact_focus");
            showLabel(label);
            $(padre).css("border-color", "rgb(156, 152, 152)");
        })
    }
});

function dateInput(){
    //agregamos estilos al input de tipo date
    $(".date_input").on("focus", function (e) {
        if(!document.startViewTransition){
            $(e.target).addClass("date_input_focus");
            return ;
        }
        document.startViewTransition(()=>{
            $(e.target).addClass("date_input_focus");
        })
    });
    $(".date_input").on("blur", function (e) {
        $(e.target).removeClass("date_input_focus");
    });
}

dateInput();

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

//enviamos los datos del formulario
$(".continue_botton").on("click", function (e) {
    e.preventDefault();

    let formData = $(".register_data").serialize();

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
        }
    });
});
//con la url obtenida navegamos a el siguiente paso de registro
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