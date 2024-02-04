const textareaPost = $("#textarea_post");

const textareContainer = $(".textarea_container");
const textareLengthContainer = $(".textarea_length_container");
const currentLengthTextarea = $(".current_length_textarea");


$(textareaPost).on("input", function (e) {
    $(e.target).css("height", "50px");
    $(e.target).css("height", e.target.scrollHeight + 'px');

    if($(textareLengthContainer).attr("style") == undefined){
        $(currentLengthTextarea).text(e.target.value.length);
        $(textareLengthContainer).css("display","flex");
    }
    else{
        changeValueLenth(currentLengthTextarea,e.target.value.length);
    }
});


$(textareaPost).on("blur", function () {
    $(textareLengthContainer).removeAttr("style");
});

function changeValueLenth(target,value){
    if(value >= 280){
        $(target).css("color", "red");
        $(target).text(value);
    }
    else{
        $(target).removeAttr("style");
        $(target).text(value)
    }
}
