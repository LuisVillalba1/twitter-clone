import {showPosts} from "../../home.js";
import {createFollow} from "../../profile/follow/utils/utilFollow.js";
import { showComment } from "./utils/utilsComments.js";
import { createIntersectionObserver } from "../../utils/utilsIntersection.js";

const mainContent = $(".search_content");
const mainContainer = $(".main_content");


function getData(page){
    let url = "";
    if(page == 0){
        let queryData = window.location.search;
        url = window.location.origin + "/searchData" + queryData
    }
    else{
        url = page;
    }

    $.ajax({
        type: "GET",
        url: url,
        success: function (response) {
            let type = response.type;
            let data = response.data;
            console.log(data);
            if(data){
                let nextPage = data.next_page_url;
                showData(type,data.data,nextPage);
            }
        },
        error : function (error){
            console.log(error);
        }
    });
}

getData(0);

function showData(type,data,nextPage){
    if(type == "posts"){
        //mostramos los posteos
        showPosts(data,mainContent,getData.bind(null,nextPage));
    }
    if(type == "profiles"){
        for(let i of data){
            createFollow(i,mainContent,mainContainer)
        }
        getData.bind(null,nextPage);
    }
    if(type == "comments"){
        for(let i of data){
            showComment(i,mainContent,mainContainer)
        }
        createIntersectionObserver(".current_post",true,null,getData.bind(null,nextPage))
    }
}
