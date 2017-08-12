<?php
require_once 'dbInfo.php';
require_once 'helperFunctions.php';

/*Required POST parameters
collage
*/

/*Returned JSON object format
{
    "status":"number",
    "status_message":"string",
    "data":[array of {
        "GID":"number",
        "BUYER":"number",
        "PRICE":"number",
        "COLLAGE":"string",
        "USERS":[array of numbers denoting user ids]
    }]
}
*/

//$collage = 'DAIICT';

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

$query = $db -> prepare("SELECT GID,USERS,BUYER,PRICE,COLLAGE FROM $DB_table_groups WHERE COLLAGE=?");
if(!$query){
    sendResponce(500, "Wrong query prepare statement", null);
    exit(1);
}

if(!$query->bind_param("s", $collage)){
    sendResponce(500, "Wrong query parameters", null);
    exit(1);
}

if(!$query->execute()){
    sendResponce(500, "Database Error-" . $query->errno . ": could not search the database", null);
    exit(1);
}

$result = $query -> get_result();
$data = [];

for($i=0; $i<$result->num_rows; $i++){
    $result -> data_seek($i);
    $row = $result -> fetch_assoc();
    $temp['GID'] = $row['GID'];
    $temp['BUYER'] = $row['BUYER'];
    $temp['PRICE'] = $row['PRICE'];
    $temp['COLLAGE'] = $row['COLLAGE'];
    $temp['USERS'] = getArrayFromString($row['USERS'], $DB_separator_1);

    array_push($data, $temp);
}

sendResponce(200, "Matching gorups found", $data);

?>