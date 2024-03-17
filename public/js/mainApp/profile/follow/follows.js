$.ajax({
    type: "get",
    url: window.location.href + "/details",
    success: function (response) {
        console.log(response) 
    },
    error : function(e){
        console.log(e)
    }
});