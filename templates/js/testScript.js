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
});
