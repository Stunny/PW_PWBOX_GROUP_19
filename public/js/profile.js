$(document).ready(()=>{

  $("#profileLink").on("click", (e)=>{
    $('#confirmPassModal').modal('show');
  e.preventDefault();
  })

  $("#deleteAccountButton").on("click", (e)=>{
    $('#deleteAccModal')
  .modal('show');
  e.preventDefault();
  })

  $("#deleteConfirmButton").on("click", (e)=>{
    $('#deleteAccModal2')
  .modal('show');
  e.preventDefault();
  })

  $('#progBar').progress({
  percent: 77
  })

});

userId = document.cookie.match(/user=[^;]+/)[0].split('=')[1];

window.onload = function loadDataProfile(){
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

                $("#profileName").text(get.responseJSON.res.username);
                $("#profileEmail").text(get.responseJSON.res.email);

            },
            404: function () {
                alert("Data not found");
            }
        }
    });
}

/*$('#loginButton').on('click',function() {
    console.log('pressed login!');
    .transition('fade');
});

$('#registerButton').on('click',function() {
    console.log('pressed register!');
});*/
/*
$(document).ready(function(){
  $("#registerButton").click(function(){
    .hide();
}
};

function checkbox() {
    var check = document.getElementById("conditionsCheck");
    var text = document.getElementById("text");
    if (check.checked == true){
        text.style.display = "block";
    }else{
        text.style.display = "none";
    }
}*/
