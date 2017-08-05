<?php
require_once 'dbInfo.php';
require_once 'helperFunctions.php';

/*Required POST parameters
uid
gid
*/

//$uid = 1;
//$gid = 2;

header('Content-Type: application/json');

if(sizeof($_POST)==0) {
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);
    extract($_POST);
}

$db = new mysqli($DB_host, $DB_user, $DB_password, $DB_database);
if($db -> connect_error){
    sendResponce(500, "Connect Error-" . $db->connect_errno . ": could not connect to database", null);
    exit(1);
}

//Adding group into users table

$query = $db -> prepare("SELECT GROUPS FROM $DB_table_users WHERE UID=?");
if(!$query){
    sendResponce(500, "Wrong query prepare statement", null);
    exit(1);
}

if(!$query -> bind_param("i", $uid)){
    sendResponce(500, "Wrong query parameters", null);
    exit(1);
}

if(!$query -> execute()){
    sendResponce(500, "Database Error-" . $query->errno . ": could not find the user", null);
    exit(1);
}

$result = $query -> get_result();
if($result->num_rows != 1){
    sendResponce(500, "Invalid user id", null);
    exit(1);
}

$result = $result -> fetch_assoc();
$result = $result['GROUPS'];
$groups = $result . $DB_seperator_1 . $gid;

$query = $db -> prepare("UPDATE $DB_table_users SET GROUPS=? WHERE UID=?");
if(!$query){
    sendResponce(500, "Wrong query prepare statement", null);
    exit(1);
}

if(!$query -> bind_param("si", $groups, $uid)){
    sendResponce(500, "Wrong query parameters", null);
    exit(1);
}

if(!$query -> execute()) {
    sendResponce(500, "Database Error-" . $query->errno . ": could not add to group", null);
    exit(1);
}

//Adding user into groups table

$query = $db -> prepare("SELECT USERS FROM $DB_table_groups WHERE GID=?");
if(!$query){
    sendResponce(500, "Wrong query prepare statement", null);
    exit(1);
}

if(!$query -> bind_param("i", $gid)){
    sendResponce(500, "Wrong query parameters", null);
    exit(1);
}

if(!$query -> execute()){
    sendResponce(500, "Database Error-" . $query->errno . ": could not find the group", null);
    exit(1);
}

$result = $query -> get_result();
if($result->num_rows != 1){
    sendResponce(500, "Invalid group number", null);
    exit(1);
}

$result = $result -> fetch_assoc();
$result = $result['USERS'];
$users = $result . $DB_seperator_1 . $uid;

$query = $db -> prepare("UPDATE $DB_table_groups SET USERS=? WHERE GID=?");
if(!$query){
    sendResponce(500, "Wrong query prepare statement", null);
    exit(1);
}

if(!$query -> bind_param("si", $users, $gid)){
    sendResponce(500, "Wrong query parameters", null);
    exit(1);
}

if(!$query -> execute()){
    sendResponce(500, "Database Error-" . $query->errno . ": could not add to group", null);
    exit(1);
}else{
    sendResponce(200, "User added successfully", null);
}

?>