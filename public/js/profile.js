userId = document.cookie.match(/user=[^;]+/)[0].split('=')[1];

$(document).ready(()=>{

  $("#deleteAccountButton").on("click", (e)=>{
    $('#deleteAccModal')
  .modal('show');
  e.preventDefault();
  })

  $("#deleteConfirmButton").on("click", (e)=>{
    $('#deleteAccModal2')
  .modal('show');
      e.preventDefault();
  });

    $("#deleteConfirmButton2").on("click", (event)=> {
        deleteAccount(event)
    });
});


function deleteAccount(event) {
    var password = document.getElementById("passwordDeleteAccount").value;
    console.log(password);
    $.ajax({
        async: true,
        type: "POST",
        url: 'api/user/'+userId+'/delete',
        data: {
            password : password
        },
        statusCode: {
            200: function(){
                window.alert("Account deleted successfully");
                event.preventDefault();
                window.location.href = '/logout'
            },

            404: function () {
                window.alert('Error! Something went wrong.');
            },

            500: function () {
                window.alert('Error! 500.');
            }
        }
    })
}

function loadDataProfile(){

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
                console.log(get.responseJSON.res.birthdate)
                console.log(get.responseJSON.res.verified)

                $("#profileName").text(get.responseJSON.res.username);
                $("#profileEmail").text(get.responseJSON.res.email);
                $("#profileBirth").text(get.responseJSON.res.birthdate);
                $("#profileImage").attr("src", '/profile_imgs/'+get.responseJSON.res.username+'.jpg');

                if(get.responseJSON.res.verified == 1){
                    $('.ui.checkbox').checkbox('set checked');
                }else{
                    $('.ui.checkbox').checkbox('set unchecked');
                }
            },
            404: function () {
                alert("Data not found");
            }
        }
    });
}

function loadDataSpace(){
    console.log("Loaded data space");

    var get = $.ajax({
        async : true,
        type : 'get',
        url: 'api/user/'+userId+'/space',
        datatype: 'json',

        statusCode: {
            200: function(){
                console.log("Data found");
                console.log(get.responseJSON.res.space);
                var total = (get.responseJSON.res.space /1048576)*100;
                var result = total.toFixed();

                $('#progBar').progress({
                    percent: result
                })
                $('#spaceUsed').text(result+'% Space Used');

            },
            404: function () {
                alert("Data not found");
            }
        }
    });
}


window.onload = function loadAll(){
    loadDataProfile();
    loadDataSpace();
}

