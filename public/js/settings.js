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

        if(newp1 != newp2){
            console.log("Error matching");
            window.alert('Error! New password must match.');
        }else{
            console.log("Matching pass");
            if (newp1 == oldpass){
                window.alert('New password can\'t be same as old password');
            }else {
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
                            $('#passChangedModalAlert').modal('show');
                            e.preventDefault();
                        },

                        403: function(){
                            window.alert('Error! Password introduced is not correct.');
                            console.log("403 bruh")
                        },

                        404: function () {
                            window.alert('Error! Something went wrong.');
                            alert("404 nope");
                        }
                    }
                });
                //TODO: VACIAR LOS CAMPOS
                //document.getElementById("newPassSettings1").innerHTML = '';
                //document.getElementById("newPassSettings2").innerHTML = "";
                //document.getElementById("newPassSettings2").textContent = "";
            }

        }
    });

    document.getElementById("changeMailSettings").onclick = function () {
        var mail = document.getElementById('newMailSettings').value;
        if (validateEmail(mail)){
            $.ajax({
                async: true,
                type: 'post',
                url: 'api/user/'+userId+'/mail',
                data: {
                    mail : mail,
                },
                statusCode: {
                    200: function(){
                        $('#mailChangedModalAlert').modal('show');
                        $("#settingsEmail").text(mail);
                        e.preventDefault();
                    },

                    403: function(){
                        console.log("403")
                    },

                    404: function () {
                        alert("404");
                    }
                }
            })
        }else {
            window.alert("Error! Email is not valid.")
        }
    };

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    $('#changeImageButton').on('click', function (){

        var img

        $.ajax({
            async : true,
            type : 'post',
            url: 'api/user/'+userId+'/profileImg',
            data: {
                oldpassword : oldpass,
                newpassword : newp1,
            },

            statusCode: {
                200: function(){
                    console.log("todo ok");
                    $('#passChangedModalAlert').modal('show');
                    e.preventDefault();
                },

                403: function(){

                    console.log("403 bruh")
                },

                404: function () {
                    alert("nope");
                }
            }
        });

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
