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
$(document).ready(()=>{
  $("#userIcon").on("click", (e)=>{
    $('.ui.labeled.icon.sidebar')
  .sidebar('toggle');
  });

  $("#profileLink").on("click", (e)=>{
    $('#confirmPassModal')
  .modal('show');
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

  $("#changeMail").on("click", (e)=>{
    $('#changeMailModal')
  .modal('show');
  e.preventDefault();
  })

  $("#changePassButton").on("click", (e)=>{
    $('#changePassModal')
  .modal('show');
  e.preventDefault();
  })

  $("#changeImageButton").on("click", (e)=>{
    $('#changeImageModal')
  .modal('show');
  e.preventDefault();
  })


  $('#progBar').progress({
  percent: 77
});
});
