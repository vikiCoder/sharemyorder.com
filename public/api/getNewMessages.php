<?php
require_once 'dbInfo.php';
require_once 'helperFunctions.php';

/*Required POST parameters
gid
currentLength
*/

//$gid = 1;
//$currentLength = 1;

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
$messages = $result['MESSAGES'];
$messages = getArrayFromString($messages, $DB_separator_1);

if(sizeof($messages)==$currentLength){
    sendResponce(200, "No new messages", null);
}else{
    for($i=$currentLength; $i<sizeof($messages); $i++){
        $temp = getArrayFromString($messages[$i], $DB_separator_2);
        $data[$i-$currentLength] = ['sender'=>$temp[0], 'message'=>$temp[1]];
    }

    sendResponce(200, "There are some new messages", $data);
}

?>