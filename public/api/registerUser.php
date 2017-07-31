<?php
require_once 'dbInfo.php';
require_once 'helperFunctions.php';

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

extract($_POST);
$db = new mysqli($DB_host, $DB_user, $DB_password, $DB_database);
$query = $db -> prepare("INSERT INTO $DB_table_users (UNAME, FNAME, LNAME, EMAIL, PASSWORD, MOBILE, PINCODE, COLLAGE) VALUES (?,?,?,?,?,?,?,?)");
$query -> bind_param('ssssssss', $uname, $fname, $lname, $email, $password, $mobile, $pincode, $collage);

if($query -> execute()){
    sendResponce(200, "ok", null);
}else{
    sendResponce(500, "Database Error-" . $query->errno . ": could not register the user", null);
    return;
}

?>