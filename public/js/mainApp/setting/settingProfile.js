$(".personal_data_input").each(function (indexInArray, valueOfElement) { 
    
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

    //controlamos la cantidad maxima que se puede tipear en los inputs y textareas
    $(valueOfElement).on("input",function(e){
        //obtenemos el padre
        let padre = e.target.closest(".input_container");

        //obtenemos el contenedor que muestra el maximo y la cantidad actual del input o textarea
        let maxLenghtContainer = $(padre).children(".max_length_container");
        let maxLength = parseInt($(maxLenghtContainer).children(".max_length").text());
        let currentLength = $(maxLenghtContainer).children(".current_length");

        //mostramos la cantidad actual de largo del input o textarea
        if($(maxLenghtContainer).attr("style") == undefined){
            $(maxLenghtContainer).css("display","flex");
            $(currentLength).text(e.target.value.length);
        }
        else{
            changeValueLenth(currentLength,e.target.value.length,maxLength);
        }
    })
});

//definimos las variables, las cuales van a almacenar las correspondientes imagenes
let coverPhoto = null;
let perfilPhoto = null;

//en caso de que se pase el maximo, coloreamos de color rojo
function changeValueLenth(target,value,maxlength){
    if(value > maxlength){
        $(target).css("color", "red");
        $(target).text(value);
    }
    else{
        $(target).removeAttr("style");
        $(target).text(value)
    }
}

//mostramos el label
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

//editar la foto de portada
const coverPhotoContainer = $(".img_cover_photo_edit");
const inputCoverPhoto = $("#input_cover_photo");

//editar la foto de perfil
const perfilPhotoContainter = $(".photo_profile_edit")
const inputPerfilPhoto = $("#input_profile_photo");


//perimitimos seleccionar un archivo al usuario 
$(coverPhotoContainer).on("click", function (e) {
    inputCoverPhoto.click();
});

$(perfilPhotoContainter).on("click", function () {
    inputPerfilPhoto.click();
});



//obtenemos el archivo seleccionado
$(inputCoverPhoto).on("change", function (e) {
    createFileReader(e.target.files[0],"cover");
});

$(inputPerfilPhoto).on("change",function(e){
    createFileReader(e.target.files[0],"perfil");
})

//controlamos cuando se esta por encima, por fuera y cuando se dropea un elemento
$(coverPhotoContainer).on("dragover", function (e) {
    e.preventDefault()

    $(coverPhotoContainer).css("border", "1px dashed rgb(72, 152, 232)");
});

$(coverPhotoContainer).on("dragleave", function (e) {
    e.preventDefault();

    $(coverPhotoContainer).css("border", "none");
});

$(coverPhotoContainer).on("drop", function (e) {
    e.preventDefault();

    $(coverPhotoContainer).css("border", "none");
    let file = e.originalEvent.dataTransfer.files;
    

    createFileReader(file[0],"cover")
});

$(perfilPhotoContainter).on("dragover", function (e) {
    e.preventDefault()

    $(perfilPhotoContainter).css("border", "1px dashed rgb(0 0 0)");
});


$(perfilPhotoContainter).on("dragleave", function (e) {
    e.preventDefault();

    $(perfilPhotoContainter).css("border", "none");
});

$(perfilPhotoContainter).on("drop", function (e) {
    e.preventDefault();

    let file = e.originalEvent.dataTransfer.files;

    $(perfilPhotoContainter).css("border", "none");

    createFileReader(file[0],"perfil")
});


let cropper = null;

//creamos una nueva url para la imagen
function createFileReader(file,typePhoto){
    try{
        checkTypeFiles(file.type,typePhoto);
        let fileReader = new FileReader();
    
        fileReader.addEventListener("load",e=>{
            const fileUrl = fileReader.result;
    
            //mostramos la imagen de perfil
            if(typePhoto == "perfil"){
                perfilPhoto = file;
                return showProfilePhoto(fileUrl)
            }
    
            ocultChildrens();
            //mostramos la imagen en el contenedor de edit
            showEditImg(file.name,fileUrl,typePhoto);
            $(".edit_cropper_container").css("display","block")
    
            let img = document.querySelector(".cropper_photo")
    
    
            if(typePhoto == "cover"){
            //ocultamos el icono de la camara
            $("#icon_camera_edit").css("display", "none");
    
            showCrapperEdit(img,800,250)
            }
        })
        fileReader.readAsDataURL(file);
    }
    catch(e){
        $(`.error_${typePhoto}`).text(e.message)
    }
}
//mostramos la foto editable
function showEditImg(name,url,typeImg){
    $(".cropper_photo").attr("src", url);
    $(".cropper_photo").attr("alt", name);
    $(".cropper_photo").attr("id",typeImg)

}

function showProfilePhoto(url){
    $(".profile_photo").attr("src",url);
    $(".profile_photo").css("width","100%")
}

//ocultamos los hijo a la hora de mostrar el contenedor de editado de fotos
function ocultChildrens(){
    $(".edit_profile_container").children().each( function (indexInArray, valueOfElement) { 
         if(indexInArray != 0){
            $(valueOfElement).css("display", "none")
         }
    });
}


//eliminamos la foto y cerramos el contenedor para editar la foto
$("#close_edit_photo_cropper").on("click", function () {
    //mostramos los hijos
    showChilds()

    //eliminamos la imagen anteriormente colocada
    $(".cropper_photo").removeAttr("src");
    $(".cropper_photo").removeAttr("alt");
    $(".cropper_photo").removeAttr("id");
});



//ocultamos el editado de la foto y mostramos de nuevo el formulario
function showChilds(){
    $(".edit_profile_container").children().each( function (indexInArray, valueOfElement) { 
        if(indexInArray == 0){
           $(valueOfElement).css("display", "none")
        }
        else if(indexInArray == 2){
            $(valueOfElement).css("display","block")
        }
        else{
            $(valueOfElement).css("display","flex")
        }
        $("#icon_camera_edit").css("display", "block");
   });
}

// creamos un nuevo cropper para poder editar nuestra imagen
function showCrapperEdit(image,widthImg,heightImg){
    if(cropper != null){
        cropper.destroy();
    }
    cropper = new Cropper(image, {
        aspectRatio: widthImg/heightImg,
        autoCropArea : 1,
        scalable : false,
        cropBoxResizable : false,
        scalable : false,
        dragMode : "none",
      });
}


//permitimos guarda la foto
$(".save_edit_cropper").on("click", function () {
    //verificamos que se haya agregado una imagen
    if($(".cropper_photo ").attr("src") && $(".cropper_photo").attr("alt")){
        ocultChildrens();

        let typePhoto = $(".cropper_photo ").attr("id");

        getCroppedCanvas();

        showChilds();
    }
});


//obtenemos la imagen
function getCroppedCanvas(){
    
    let croppedImg = cropper.getCroppedCanvas();
    
    croppedImg.toBlob(function(blob) {
        // Crear un nuevo objeto File con el Blob y datos adicionales
        let croppedFile = new File([blob], 'cropped_image', { type: 'image/jpeg' });

        // Realizar las operaciones necesarias con el archivo recortado, como enviarlo al servidor

        let urlImg = URL.createObjectURL(croppedFile);

        $(".img_cover_photo").attr("src",urlImg);

        coverPhoto = croppedFile;
        
    }, 'image/jpeg');
}


$(".save_edit").on("click",function(){
    //obtenemos los datos de los formulario y ademas le sumamos las fotos correspondientes
    let formData = new FormData($(".personal_data_edit")[0]);
    if(coverPhoto != null){
        formData.append("coverPhoto",coverPhoto)
    }
    if(perfilPhoto != null){
        formData.append("profilePhoto",perfilPhoto);
    }

    sendForm(formData);
})

//enviamos el formulario
function sendForm(formData){
    $.ajax({
        type: "POST",
        url: $(".personalda_data_edit").attr("action"),
        data : formData,
        //el content type y procces data nos sirve para enviar imagenes
        contentType: false,
        processData: false,
        success: function (response) {
            window.location.href = response;
        },
        error:function(error){
            let errores = error.responseJSON.errors;
            if(error.status == 422){
                $.each(errores, function (indexInArray, valueOfElement) {
                     $(`.errors_${indexInArray}`).text(valueOfElement)
                });
            }
            if(error.status == 500){
                $(".errors").text(errores);
            }
        }
    });
}

//verificamos que la imagen subida tenga un formato correcto
function checkTypeFiles(type,photo){
    const validationTypes = ["image/jpeg","image/jpg","image/png"];

    if(!validationTypes.includes(type)){ 
        throw new Error("Archivo incorrecto, solo se permiten archivos jpeg,jpg y png");
    }
}





