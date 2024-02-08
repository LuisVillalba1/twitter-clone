//seleccionamos todos los posts
export function createIntersectionObserver(postsNames){
    //obtenemos todos los post
    let posts = document.querySelectorAll(postsNames);

    posts.forEach(post=>{
        let observer = new IntersectionObserver(chekIntersection,{});
        observer.observe(post)
    })
}

//obtenemos el post visualizado
function chekIntersection(e){
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
