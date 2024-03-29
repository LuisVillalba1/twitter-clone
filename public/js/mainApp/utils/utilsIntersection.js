//seleccionamos todos los posts
//nuestra funcion va recibir un callback la cual mostrara mas post en caso de que ya se haya visto el ultimo
export function createIntersectionObserver(postsNames,visualization,getID,callback){
    //obtenemos todos los post
    let posts = document.querySelectorAll(postsNames);

    //con el metodo forEach iteramos sobre cada post y con el index obtenemos la posicion del ultimo post
    posts.forEach((post,index)=>{
        //verificamos cuando el ultimo post ha sido interceptado
        let isLastPost = index == posts.length - 1;
        //verificamos la interseccion de cada post
        let observer = new IntersectionObserver(entries=>chekIntersection(entries,isLastPost,observer,visualization,getID,callback),{});
        observer.observe(post)
    })
}

//obtenemos el post visualizado
function chekIntersection(e,isLastPost,observer,visualizationValue,getID,callback){
    //obtenemos el post que se ha visualidado
    let data = e[0];
    let post = data.target;
    let lastChild = post.lastElementChild;
    let visualization = lastChild.lastElementChild

    //en caso de que el usuario lo haya visto y se desee enviar la visuzalizacion,obtenemos los datos del post y la enviamos al servidor
    if(data.isIntersecting && visualizationValue != false){
        let actionVisualization = $(visualization).attr("action");
        sendVisualization(actionVisualization);
    }
    //si se observa el ultimo post detenemos el observer y mostramos mas posts en caso de que existan
    if(isLastPost && data.isIntersecting){
        observer.disconnect()
        if(getID){
            let id = $(post).attr("id");
            return callback(parseInt(id))
        }
        callback()
        return
    }
}

//enviamos que se ha visualizado el post al sevidor
function sendVisualization(url){
    $.ajax({
        type: "POST",
        url: url,
        success: function (response) {
            
        },
        error : function(error){
            console.log(error)
        }
    });
}

