const dragContainer = $(".new_post_content_container");
const inputFile = $("#upload_img_input");
const imgsContainer = $(".imgs_container");
const errorFileContainer = $(".error_files_container")

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

    const validationTypes = ["image/jpeg","image/jpg","image/png","image/gif"];

    if(!validationTypes.includes(fileType)){
        let parrafo = $("<p></p>");
        $(parrafo).addClass("error_file");
        $(parrafo).text("Archivo incorrecto, solo se permiten archivos jpeg,jpg,png y gif");
        return $(errorFileContainer).append(parrafo);
    }

    let fileReader = new FileReader();

    fileReader.addEventListener("load",e=>{
        const fileUrl = fileReader.result;
        showNewImg(file.name,fileUrl);
    })

    fileReader.readAsDataURL(file);
}


function showNewImg(name,src){
    let imgContainer = $("<div></div>");
    $(imgContainer).addClass("new_img_container");

    let img = $("<img></img>");
    $(img).addClass("new_img_container__img");
    $(img).attr("src", src);
    $(img).attr("alt",name);

    $(imgContainer).append(img);
    $(imgsContainer).append(imgContainer);
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