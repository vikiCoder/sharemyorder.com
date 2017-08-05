<?php
require_once 'dbInfo.php';
require_once 'helperFunctions.php';

/*Required POST parameters
gid
*/

//$gid = 2;

header('Content-Type: application/json');

if(sizeof($_POST)==0) {
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);
    extract($_POST);
}

//delete grop from all member users

$db = new mysqli($DB_host, $DB_user, $DB_password, $DB_database);
if($db -> connect_error){
    sendResponce(500, "Connect Error-" . $db->connect_errno . ": could not connect to database", null);
    exit(1);
}

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
$result = $result -> fetch_assoc();
$result = $result['USERS'];

foreach(getArrayFromString($result, $DB_seperator_1) as $uid){
    ob_start();
    include 'removeUserFromGroup.php';
    ob_end_clean();
}

//delete group from groups table

$query = $db -> prepare("DELETE FROM $DB_table_groups WHERE GID=?");
if(!$query){
    sendResponce(500, "Wrong query prepare statement", null);
    exit(1);
}

if(!$query -> bind_param("i", $gid)){
    sendResponce(500, "Wrong query parameters", null);
    exit(1);
}

if($query -> execute()){
    sendResponce(200, "Group successfully deleted", null);
}else{
    sendResponce(500, "Database Error-" . $query->errno . ": could not delete the group", null);
    exit(1);
}

?>