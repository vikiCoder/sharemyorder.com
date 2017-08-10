<?php
require_once 'dbInfo.php';
require_once 'helperFunctions.php';

/*Required POST parameters
gid
uname
message
*/

//$gid = 1;
//$uname = 'a';
//$message = "I am fine.";

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

$query = $db -> prepare("SELECT MESSAGES FROM $DB_table_groups WHERE GID=?");
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
$messages = $result['MESSAGES'] . $DB_seperator_1 . $uname . $DB_seperator_2 . $message;

$query = $db -> prepare("UPDATE $DB_table_groups SET MESSAGES=? WHERE GID=?");
if(!$query){
    sendResponce(500, "Wrong query prepare statement", null);
    exit(1);
}

if(!$query -> bind_param("si", $messages, $gid)){
    sendResponce(500, "Wrong query parameters", null);
    exit(1);
}

if(!$query -> execute()){
    sendResponce(500, "Database Error-" . $query->errno . ": could not send the message", null);
    exit(1);
}else{
    sendResponce(200, "Message added successfully", null);
}

?>