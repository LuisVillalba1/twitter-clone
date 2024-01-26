const dragContainer = $(".new_post_content_container");
const inputFile = $("#upload_img_input");
const imgsContainer = $(".imgs_container");
const errorFileContainer = $(".error_files_container")

let formImgData = new FormData();
let images = [];

let files;

//get files from input
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
    const fileType = file.type;
    const maxSize = 5 * 1024 * 1024;

    const validationTypes = ["image/jpeg","image/jpg","image/png","image/gif"];

    if(!validationTypes.includes(fileType)){
        let parrafo = $("<p></p>");
        $(parrafo).addClass("error_file");
        $(parrafo).text("Archivo incorrecto, solo se permiten archivos jpeg,jpg,png y gif");
        return $(errorFileContainer).append(parrafo);
    }

    if(file.size > maxSize){
        let parrafoZice = $("<p></p>");
        $(parrafoZice).addClass("error_file")
        $(parrafoZice).text("Solo se admiten imagenes que pesen menos de 5 megas"); 
    }

    let fileReader = new FileReader();

    fileReader.addEventListener("load",e=>{
        const fileUrl = fileReader.result;
        showNewImg(file.name,fileUrl);
        images.push({
            name : file.name,
            url : fileUrl
        })
    })
    fileReader.readAsDataURL(file);
}


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

$(dragContainer).on("drop", function (e) {
    e.preventDefault();
    $(dragContainer).css("border", "none");
    let files= e.originalEvent.dataTransfer.files;
    showFiles(files);
});

// <i class="fa-solid fa-xmark"></i>
function deleteImg(icon){
    $(icon).on("click", function (e) {
        let padre = e.target.closest(".new_img_container");
        let img = $(padre).children().last();

        let name = $(img).attr("alt");
        let url = $(img).attr("src");

        images = images.filter(item=>!(item.name == name && item.url == url));

        $(padre).remove();
    });
}

const postBotton = $(".send_and_add_content__repost");


$(postBotton).on("click", function (e) {
    sendForm();
});

function sendForm(){
    let formData = $(".new_post").serialize();

    let imagesData = JSON.stringify(images);

    // Agregar la cadena JSON al formulario
    formData += "&images=" + encodeURIComponent(imagesData);

    $.ajax({
        type: "POST",
        url: $(".new_post").attr("action"),
        data: formData,
        success: function (response) {
            console.log(response)
        },
        error: function(error){
            console.log("bad")
        }
    });
}

