//seleccionamos todos los posts
//nuestra funcion va recibir un callback la cual mostrara mas post en caso de que ya se haya visto el ultimo
export function createIntersectionObserver(postsNames,callback){
    //obtenemos todos los post
    let posts = document.querySelectorAll(postsNames);
    console.log(posts)

    //con el metodo forEach iteramos sobre cada post y con el index obtenemos la posicion del ultimo post
    posts.forEach((post,index)=>{
        //verificamos cuando el ultimo post ha sido interceptado
        let isLastPost = index == posts.length - 1;
        //verificamos la interseccion de cada post
        let observer = new IntersectionObserver(entries=>chekIntersection(entries,isLastPost,observer,callback),{});
        observer.observe(post)

    })
}

//obtenemos el post visualizado
function chekIntersection(e,isLastPost,observer,callback){
    //obtenemos el post que se ha visualidado
    let data = e[0];
    let post = data.target;
    let lastChild = post.lastElementChild;
    let visualization = $(lastChild.lastChild)

    //en caso de que el usuario lo haya visto obtenemos los datos del post y la enviamos al servidor
    if(data.isIntersecting){
        let actionVisualization = $(visualization).attr("action");
        sendVisualization(actionVisualization);
    }
    //si se observa el ultimo post detenemos el observer y mostramos mas posts en caso de que existan
    if(isLastPost && data.isIntersecting){
        console.log(data.isIntersecting)
        observer.disconnect()
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
            // console.log(response);
        },
        error : function(error){
            console.log(error)
        }
    });
}
