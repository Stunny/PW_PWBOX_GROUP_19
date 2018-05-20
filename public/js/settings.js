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

    $('#changePassSettings').on("click", function changePass(){

        var oldpass = document.getElementById('oldPassSettings').value;
        var newp1 = document.getElementById('newPassSettings1').value;
        var newp2 = document.getElementById('newPassSettings2').value;
        console.log("changePass function called");
        console.log(oldpass);
        console.log(newp1);
        console.log(newp2);

        if(!newp2.match(newp1)){
            console.log("error matching")
        }else{
            console.log("Matching pass")
        }

        $.ajax({
            async : true,
            type : 'post',
            url: 'api/user/'+userId+'/password',
            data: {
                oldpassword : oldpass,
                newpassword : newp1,
            },

            statusCode: {
                200: function(){
                    console.log("todo ok");
                },

                403: function(){

                    console.log("403 bruh")
                },

                404: function () {
                    alert("nope");
                }
            }
        });

    });

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
