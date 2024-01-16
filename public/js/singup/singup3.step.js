$(".continue_botton").on("click", function (e) {
    let formData = $(".register_data").serialize();
    $.ajax({
        type: "post",
        url: $(".register_data").attr("action"),
        data : formData,
        success: function (response) {
            showStep4(response);
        },
        error: function(error){
            $(".error_numbers").empty();
            if(error.status == 422){
                let errores = error.responseJSON.errors;
                $.each(errores, function (indexInArray, valueOfElement) {
                    let nuevo_parrafo = $("<p></p>")
                    $(nuevo_parrafo).text(valueOfElement);
                    $(".error_numbers").append(nuevo_parrafo)

                });
            }
            else{
                let errores = error.responseJSON.error;
                let nuevo_parrafo = $("<p></p>");
                $(nuevo_parrafo).text(errores);
                $(".error_numbers").append(nuevo_parrafo)
            }
        }
    });
});

function showStep4(url){
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