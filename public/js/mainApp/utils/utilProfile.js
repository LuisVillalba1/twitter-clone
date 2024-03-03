export function setLinks(container,relativeRoute){
    let parts = window.location.href.split("/");
    parts.pop();
    let newUrl = parts.join("/");
    
    if(relativeRoute){
        newUrl += `/${relativeRoute}`
    }

    //seteamos el nuevo link
    $(container).children().attr("href",newUrl)
}