const textareaPost = $("#textarea_post");

$(textareaPost).on("input", function (e) {
    $(e.target).css("height", "50px");
    $(e.target).css("height", e.target.scrollHeight + 'px');
});

const iconUploadImg = $(".icon_upload_img");
const inputUploadImg = $("#upload_img_input");

$(iconUploadImg).on("click", function () {
    inputUploadImg.click();
});