import { createFollow } from "./utils/utilFollow.js";
import * as utilsIntersection from "../../utils/utilsIntersection.js"
import { createErrorAlert } from "../../utils/error/errorAlert.js";

//obtenemos los seguidores del usuario
function getFollowers(url){
    $.ajax({
        type: "get",
        url: url,
        success: function (response) {
            if(!response.data){
                return
            }
            let nextPage = response.next_page_url;
            showFollowers(response.data,nextPage)
        },
        error : function (e){
            createErrorAlert(e.responseJSON.errors,$(".follow_container"))
        }
    });
}

//mostramos los seguidos
function showFollowers(data,nextPage){
    for(let i of data){
        createFollow(i.personal_data_follower);
    }
    utilsIntersection.createIntersectionObserver(".follow_user_container",false,false,getFollowers.bind(null,nextPage))
}

getFollowers(window.location.href + "/details");