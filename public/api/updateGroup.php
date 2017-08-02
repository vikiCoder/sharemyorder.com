<?php
require_once 'dbInfo.php';
require_once 'helperFunctions.php';

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);
extract($_POST);

$db = new mysqli($DB_host, $DB_user, $DB_password, $DB_database);
if($db -> connect_error){
    sendResponce(500, "Connect Error-" . $db->connect_errno . ": could not connect to database", null);
    $db -> close();
    return;
}

if(isset($users)){

    $query = $db -> prepare("SELECT USERS FROM $DB_table_groups WHERE GID=?");
    if(!$query){
        sendResponce(500, "Wrong query prepare statement", null);
        $db -> close();
        return;
    }

    if(!$query -> bind_param("i", $gid)){
        sendResponce(500, "Wrong query parameters", null);
        $query -> close();
        $db -> close();
        return;
    }

    if(!$query -> execute()){
        sendResponce(500, "Database Error-" . $query->errno . ": could not find the group", null);
        $query -> close();
        $db -> close();
        return;
    }

    $result = $query -> get_result();
    if($result->num_rows != 1){
        sendResponce(500, "Invalid group number", null);
        $result -> close();
        $query -> close();
        $db -> close();
        return;
    }

    $result = $result -> fetch_assoc();
    $result = $result['USERS'];
    $users = $result . $DB_seperator_1 . $users;

    $query -> close();
    $query = $db -> prepare("UPDATE $DB_table_groups SET USERS=? WHERE GID=?");
    if(!$query){
        sendResponce(500, "Wrong query prepare statement", null);
        $db -> close();
        return;
    }

    if(!$query -> bind_param("si", $users, $gid)){
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

}else if(isset($messages)){

    $query = $db -> prepare("SELECT MESSAGES FROM $DB_table_groups WHERE GID=?");
    if(!$query){
        sendResponce(500, "Wrong query prepare statement", null);
        $db -> close();
        return;
    }

    if(!$query -> bind_param("i", $gid)){
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
    }

    $result = $result -> fetch_assoc();
    $messages = $result['MESSAGES'] . $DB_seperator_1 . $uname . $DB_seperator_2 . $messages;

    $query = $db -> prepare("UPDATE $DB_table_groups SET MESSAGES=? WHERE GID=?");
    if(!$query){
        sendResponce(500, "Wrong query prepare statement", null);
        $db -> close();
        return;
    }

    if(!$query -> bind_param("si", $messages, $gid)){
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

}

?>