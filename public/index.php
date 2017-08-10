<?php

    if(isset($_COOKIE['UID'])){
        header("Location: mainpage.html");
    }else{
        header("Location: homepage.html");
    }

?>