

let box = document.querySelector(".box");

box.addEventListener("click",(e)=>{
    box.setAttribute("style","view-transition-name : box");
    document.startViewTransition(()=>{
        e.target.classList.toggle("move");
    })
})