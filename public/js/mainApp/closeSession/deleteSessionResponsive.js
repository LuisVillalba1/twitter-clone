const formDeleteResponsive = $("#delete_session_form");
function deleteSession(){
    $(formDeleteResponsive).on("click", function (e) {
        e.preventDefault();

        let action = $(formDeleteResponsive).attr("action");
        deleteSessionFetch(action);
    });
}

function deleteSessionFetch(url){
    $.ajax({
        type: "DELETE",
        url: url,
        data : $(formDeleteResponsive).serialize(),
        success: function (response) {
            window.location.href = response;
        },
        error : function(error){
            console.log(error);
        }
    });
}

deleteSession();