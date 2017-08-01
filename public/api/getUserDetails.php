<?php
require_once 'dbInfo.php';

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);
extract($_POST);

$password = hash('sha512', $DB_salt + $password);

$db = new mysqli($DB_host, $DB_user, $DB_password, $DB_database);
if($db -> connect_error){
    sendResponce(500, "Connect Error-" . $db->connect_errno . ": could not connect to database", null);
    $db -> close();
    return;
}

if(isset($uid)) {

    $query = $db->prepare("SELECT UID,UNAME,FNAME,LNAME,EMAIL,MOBILE,PINCODE,COLLAGE,GROUPS FROM $DB_table_users WHERE UID=?");
    if(!$query){
        sendResponce(500, "Wrong query prepare statement", null);
        $db -> close();
        return;
    }

    if(!$query -> bind_param("i", $uid)){
        sendResponce(500, "Wrong query parameters", null);
        $query -> close();
        $db -> close();
        return;
    }

    if(!$query -> execute()){
        sendResponce(500, "Database Error-" . $query->errno . ": could not get the user", null);
        $query -> close();
        $db -> close();
        return;
    }

    $result = $query -> get_result();
    if($result->num_rows != 1){
        sendResponce(500, "Invalid login details", null);
        $result -> close();
        $query -> close();
        $db -> close();
        return;
    }else{
        sendResponce(200, "ok", $result->fetch_assoc());
        $result -> close();
        $query -> close();
        $db -> close();
        return;
    }

}else{

    $query = $db->prepare("SELECT UID,UNAME,FNAME,LNAME,EMAIL,MOBILE,PINCODE,COLLAGE,GROUPS FROM $DB_table_users WHERE EMAIL=? AND PASSWORD=?");
    if(!$query){
        sendResponce(500, "Wrong query prepare statement", null);
        $db -> close();
        return;
    }

    if(!$query -> bind_param("ss", $email, $password)){
        sendResponce(500, "Wrong query parameters", null);
        $query -> close();
        $db -> close();
        return;
    }

    if(!$query -> execute()){
        sendResponce(500, "Database Error-" . $query->errno . ": could not get the user", null);
        $query -> close();
        $db -> close();
        return;
    }

    $result = $query -> get_result();
    if($result->num_rows != 1){
        sendResponce(500, "Invalid login details", null);
        $result -> close();
        $query -> close();
        $db -> close();
        return;
    }else{
        sendResponce(200, "ok", $result->fetch_assoc());
        $result -> close();
        $query -> close();
        $db -> close();
        return;
    }

}


?>