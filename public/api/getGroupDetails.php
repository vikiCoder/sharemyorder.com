<?php
require_once 'dbInfo.php';
require_once 'helperFunctions.php';

/*Required POST parameters
gid
*/

$gid = 1;

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

$query = $db -> prepare("SELECT USERS,BUYER,ITEMS,PRICE,COLLAGE FROM $DB_table_groups WHERE GID=?");
if(!$query){
    sendResponce(500, "Wrong query prepare statement", null);
    exit(1);
}

if(!$query->bind_param("i", $gid)){
    sendResponce(500, "Wrong query parameters", null);
    exit(1);
}

if(!$query->execute()){
    sendResponce(500, "Database Error-" . $query->errno . ": could not get the details", null);
    exit(1);
}

$result = $query -> get_result();
if($result->num_rows != 1){
    sendResponce(500, "Invalid login details", null);
    exit(1);
}else{

    $result = $result->fetch_assoc();
    $data['BUYER'] = $result['BUYER'];
    $data['PRICE'] = $result['PRICE'];
    $data['COLLAGE'] = $result['COLLAGE'];

    $users = getArrayFromString($result['USERS'], $DB_seperator_1);
    $items = getArrayFromString($result['ITEMS'], $DB_seperator_1);

    foreach ($users as $user){
        $temp = [];
        foreach ($items as $item){
            $item = getArrayFromString($item, $DB_seperator_2);
            if($item[0] == $user)
                array_push($temp, $item[1]);
        }
        $details["$user"] = $temp;
    }

    $data['USERS'] = $details;

    sendResponce(200, "Details found", $data);

}

?>