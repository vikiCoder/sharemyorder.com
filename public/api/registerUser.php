<?php
require_once 'dbInfo.php';
require_once 'helperFunctions.php';

extract($_POST);
$db = new mysqli($DB_host, $DB_user, $DB_password, $DB_database);
$query = $db -> prepare("INSERT INTO $DB_user ('UNAME', 'FNAME', 'LNAME', 'EMAIL', 'MOBILE', 'PINCODE', 'COLLAGE') VALUES (?,?,?,?,?,?,?)");
$query -> bind_param('sssssss', $uname, $fname, $lname, $email, $mobile, $pincode, $collage);

if($query -> execute()){
    sendResponce(200, "ok", null);
}else{
    sendResponce(500, "Database Error-" . $query->errno . ": could not register the user", null);
    return;
}

?>