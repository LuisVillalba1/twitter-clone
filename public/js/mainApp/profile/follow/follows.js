import { createFollow } from "./utils/utilFollow.js";
import * as utilsIntersection from "../../utils/utilsIntersection.js"
import { createErrorAlert } from "../../utils/error/errorAlert.js";

//obtenemos los follows
function getFollows(url){
    $.ajax({
        type: "get",
        url: url,
        success: function (response) {
            if(!response.data){
                return
            }
            let nextPage = response.next_page_url;
            showFollows(response.data,nextPage);
        },
        error : function(e){
            createErrorAlert(e.responseJSON.errors,$(".follow_container"))
        }
    });
}

//mostramos los seguidos
function showFollows(data,nextPage){
    for(let i of data){
        createFollow(i.personal_data_follow);
    }
    utilsIntersection.createIntersectionObserver(".follow_user_container",false,false,getFollows.bind(null,nextPage))
}

getFollows(window.location.href + "/details");