$(document).ready(()=>{

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
    });