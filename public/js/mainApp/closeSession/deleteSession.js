const formDelete = $("#delete_session_form");
function deleteSession(){
    $(formDelete).on("click", function (e) {
        e.preventDefault();

        let action = $(formDelete).attr("action");
        deleteSessionFetch(action);
    });
}

function deleteSessionFetch(url){
    console.log(url);
    $.ajax({
        type: "DELETE",
        url: url,
        data : $(formDelete).serialize(),
        success: function (response) {
            window.location.href = response;
        },
        error : function(error){
            console.log(error);
        }
    });
}

deleteSession();