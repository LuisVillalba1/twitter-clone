export function createLoader(){
    let loaderContainer = $("<div></div>");
    $(loaderContainer).addClass("loader_container");

    let loader = $("<div></div>");
    $(loader).addClass("loader");

    $(loaderContainer).append(loader);

    return loaderContainer;
}