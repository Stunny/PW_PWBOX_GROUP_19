$(document).ready(()=>{

    $("#changeMail").on("click", (e)=>{
        $('#changeMailModal').modal('show');
        e.preventDefault();
    })

    $("#changePassButton").on("click", (e)=>{
        $('#changePassModal').modal('show');
        e.preventDefault();
    })

    $("#changeImageButton").on("click", (e)=>{
      $('#changeImageModal').modal('show');
    e.preventDefault();
    })

});
userId = document.cookie.match(/user=[^;]+/)[0].split('=')[1];


window.onload = function loadDataSettings(){
    console.log("Loaded function");
    console.log(userId);

    var get = $.ajax({
        async : true,
        type : 'get',
        url: 'api/user/'+userId,
        dataType: 'json',

        statusCode: {
            200: function(){
                console.log("Data found");
                console.log(get.responseJSON.res);
                console.log(get.responseJSON.res.username);
                console.log(get.responseJSON.res.email);

                $("#settingsName").text(get.responseJSON.res.username);
                $("#settingsEmail").text(get.responseJSON.res.email);

            },
            404: function () {
                alert("Data not found");
            }
        }
    });
}