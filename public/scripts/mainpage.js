$(document).ready(function(){

    $(".addItem").click(function(){


        alert("HELLO");
         $(".myItemform").show();
    });

});

function showItemform(){
    document.getElementById("myItemform").style.display = "block";
}
function hideForm(){
    document.getElementById("myItemform").style.display = "none";
}
function showItemformreq(){
    document.getElementById("myItemformreq").style.display = "block";
}
function hideFormreq(){
    document.getElementById("myItemformreq").style.display = "none";
}