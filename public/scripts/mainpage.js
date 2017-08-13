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

function logOut() {
    deleteCookie('UID');
    window.location = "index.php";
}

function deleteCookie(cname) {
    var d = new Date();
    d.setTime(d.getTime() + (-1*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + ";" + expires + ";path=/";
}