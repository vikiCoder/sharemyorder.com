<?php
require_once 'dbInfo.php';
require_once 'helperFunctions.php';

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

$query = $db -> prepare("INSERT INTO $DB_table_users (UNAME, FNAME, LNAME, EMAIL, PASSWORD, MOBILE, PINCODE, COLLAGE) VALUES (?,?,?,?,?,?,?,?)");
if(!$query){
    sendResponce(500, "Wrong query prepare statement", null);
    $db -> close();
    return;
}

if(!$query -> bind_param('ssssssss', $uname, $fname, $lname, $email, $password, $mobile, $pincode, $collage)){
    sendResponce(500, "Wrong query parameters", null);
    $query -> close();
    $db -> close();
    return;
}


if($query -> execute()){
    sendResponce(200, "ok", null);
    $query -> close();
    $db -> close();
}else{
    sendResponce(500, "Database Error-" . $query->errno . ": could not register the user", null);
    $query -> close();
    $db -> close();
    return;
}

?>