const dragContainer = $(".new_post_content_container");
const inputFile = $("#upload_img_input");
const imgsContainer = $(".imgs_container");
const errorFileContainer = $(".error_files_container")

//creamos un nuevo formulario
let formImgData = new FormData();
let images = [];

let files;

//obtenemos los arhivos seleccionados por parte del usuario
$(inputFile).on("change", function () {
    files = this.files;

    showFiles(files);
});


function showFiles(files){
    $(errorFileContainer).text("")
    if(files.length == undefined){
        return proccessFile(files)
    }
    for(let i of files){
        proccessFile(i);
    }
}

function proccessFile(file){
    //obtenemos el tipo de archivo y determinamos un maximo de size
    const fileType = file.type;
    const maxSize = 5 * 1024 * 1024;

    const validationTypes = ["image/jpeg","image/jpg","image/png","image/gif"];

    //verificamos que sea un formato correcto
    if(!validationTypes.includes(fileType)){
        let parrafo = $("<p></p>");
        $(parrafo).addClass("error_file");
        $(parrafo).text("Archivo incorrecto, solo se permiten archivos jpeg,jpg,png y gif");
        return $(errorFileContainer).append(parrafo);
    }

    //verificamos que contenga un size menor a 5 pixeles
    if(file.size > maxSize){
        let parrafoZice = $("<p></p>");
        $(parrafoZice).addClass("error_file")
       return $(parrafoZice).text("Solo se admiten imagenes que pesen menos de 5 megas"); 
    }
    
    //creamos una url para la imagen y la mostramos
    let fileReader = new FileReader();

    images.push(file);
    fileReader.addEventListener("load",e=>{
        const fileUrl = fileReader.result;
        showNewImg(file.name,fileUrl);
    })
    fileReader.readAsDataURL(file);
}

//segun un name y src mostramos la nueva imagen
function showNewImg(name,src){
    let imgContainer = $("<div></div>");
    let iconClose = $("<i></i>");
    $(iconClose).addClass("fa-solid fa-xmark");
    $(iconClose).addClass("new_img__close_icon")
    $(imgContainer).addClass("new_img_container");

    let img = $("<img></img>");
    $(img).addClass("new_img_container__img");
    $(img).attr("src", src);
    $(img).attr("alt",name);

    $(imgContainer).append(iconClose);
    $(imgContainer).append(img);
    $(imgsContainer).append(imgContainer);
    deleteImg(iconClose);
}

$(dragContainer).on("dragover", function (e) {
    e.preventDefault();
    $(dragContainer).css("border", "1px dashed rgb(72, 152, 232)");
});

$(dragContainer).on("dragleave", function (e) {
    e.preventDefault();
    $(dragContainer).css("border", "none");
});

//obtenemso el archivo dropeado en el contenedor
$(dragContainer).on("drop", function (e) {
    e.preventDefault();
    $(dragContainer).css("border", "none");
    let files= e.originalEvent.dataTransfer.files;
    showFiles(files);
});


//permitimos eleminar la imagen seleccionada
function deleteImg(icon){
    $(icon).on("click", function (e) {
        let padre = e.target.closest(".new_img_container");
        let img = $(padre).children().last();

        let name = $(img).attr("alt");
        let url = $(img).attr("src");

        images = images.filter(item=>!(item.name == name));

        $(padre).remove();
    });
}

const postBotton = $(".send_and_add_content__repost");

//enviamos el formulario
$(postBotton).on("click", function (e) {
    sendForm();
});

function sendForm(){
    //creamos un nuevo form data con los valores del formulario
    let formData = new FormData($(".new_post")[0]);

    //Agregar las imágenes al formData
    for (let i = 0; i < images.length; i++) {
        formData.append('images[]', images[i]);
    }

    $.ajax({
        type: "POST",
        url: $(".new_post").attr("action"),
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            console.log(response);
            window.location.href = "";
        },
        error: function(error){
            console.log(error)
        }
    });
}




