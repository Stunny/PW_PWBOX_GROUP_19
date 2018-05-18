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
  });
});

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
