import { createErrorAlert } from "../../utils/error/errorAlert.js";

const inputSearch = $(".input_search");
const resultSearchContainer = $(".results_search");
const deleteButton = $(".delete_searchs_form");

//mostramos las el contenedor de resultado
function showResultsSearch(){
    $(inputSearch).on("focus", function (e) {
        e.preventDefault();
        $(resultSearchContainer).css("display", "flex");
    });
}


//ocultamos el contenedor de resultados
function ocultResultSearch(){
$("body").on("click", function (e) {
    let target = e.target;

    //si no se clickea cerca del input o cerca del formulario el contenedor de busqueda
    if(target.closest(".results_search") || target.closest(".input_container")){
        deleteSearchs();
    }
    else{
        $(resultSearchContainer).css("display", "none");
    }
});
}


showResultsSearch();
//obtenemos las busquedas recientes
function getRecentSearchs(){
    $.ajax({
        type: "GET",
        url: window.location.origin + "/recentSearchs",
        dataType: "json",
        success: function (response) {
            if(response.data.length > 0){
                return addRecentsSearchs(response.data);
            }
            $(".recents_header_container").remove();
        },
        error : function(e){
            $(".recents_header_container").remove();
        }
    });
}

getRecentSearchs();


const resultContentContainer = $(".result_search__content");
//añadimos las busquedas recientes al contenedor de busquedas
function addRecentsSearchs(data){
    for(let i of data){
        let recentSearchContainer = $("<div></div>");
        $(recentSearchContainer).addClass("recent_search_container");

        let imgContainer = $("<div></div>");
        $(imgContainer).addClass("recent_search__img_container");

        let content = $("<a></av>");
        $(content).attr("href", i.value.Link);
        $(content).addClass("recent_search__content");

        let deleteSearch = $("<div></div>");
        $(deleteSearch).addClass("recent_search__delete_container");

        let deleteIcon = $("<i></i>");
        $(deleteIcon).addClass("fa-solid fa-xmark");
        $(deleteSearch).append(deleteIcon);

        if(i.type == "User"){
            $(recentSearchContainer).append(userImgContainer(imgContainer,i.value));
            $(recentSearchContainer).append(userContent(content,i.value));
        }

        $(recentSearchContainer).append(deleteSearch);
        $(resultContentContainer).append(recentSearchContainer);

    }
}

//agregamos la imagen del usuario en caso de que contenga osino mostramos la primera letra del usuario
function userImgContainer(container,data){
    let imgUser = data.user.profile.ProfilePhotoURL;
    let imgUserName = data.user.profile.ProfilePhotoName;

    let imgContainer = $("<div></div>");
    $(imgContainer).addClass("search_img_container");

    //en caso de que contenga una imagen la agregamos
    if(imgUser && imgUserName){
        let img = $("<img></img>");
        $(img).attr("src", imgUser);
        $(img).attr("alt",imgUserName);

        $(imgContainer).append(img);
        $(container).append(imgContainer);
        return container;
    }

    //osino solo agregamos la primera letra del usuario
    let nickname = data.Nickname;

    let nickanmeParagraph = $("<p></p>");
    $(nickanmeParagraph).addClass("search_img_container__nickname");
    $(nickanmeParagraph).text(nickname[0].toUpperCase());

    $(imgContainer).append(nickanmeParagraph);
    $(container).append(imgContainer);
    return container;
}

//añadimos la informacion del usuario,su nombre y nickname
function userContent(container,data){
    let nameTitle = $("<h5></h5>");
    $(nameTitle).addClass("user_search_title");
    $(nameTitle).text(data.user.Name);

    let nicknameParagraph = $("<p></p>");
    $(nicknameParagraph).text(`@${data.Nickname}`);

    $(container).append(nameTitle);
    $(container).append(nicknameParagraph);

    return container;
}

//en caso de que se clickeen en el lugar en borrar todo,eliminamos todas las busquedas recientes
function deleteSearchs(){
    $(deleteButton).on("click", function () {
        deleteAllSearchsFetch();
    });
}

//eliminamos todas las busquedas recientes
function deleteAllSearchsFetch(){
    $.ajax({
        type: "DELETE",
        data : $(deleteButton).serialize(),
        url: $(deleteButton).attr("action"),
        success: function (response) {
            if(response){
                $(".recent_search_container").remove();
            }
        },
        error : function (e){
            console.log(e)
        }
    });
}

ocultResultSearch();


function search(){
    $(inputSearch).on("keydown", function (e) {
        if(e.key == "Enter" && inputSearch.val().length > 0){
            let inputValue = inputSearch.val();

            window.location.href = window.location.origin + "/search?q=" + inputValue;
        }
    });
}


search();


