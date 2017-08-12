<?php
require_once 'dbInfo.php';
require_once 'helperFunctions.php';

/*Required POST parameters
Must: gid
Optional: buyer, item+uid+price, collage, address
*/

/*Returned JSON object format
{
    "status":"number",
    "status_message":"string"
}
*/

//$gid = 1;
//$buyer = 1;
//$item = 'lmn.com';
//$uid = 1;
//$price = 20;

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

if(isset($buyer)){

    $query = $db -> prepare("UPDATE $DB_table_groups SET BUYER=? WHERE GID=?");
    if(!$query){
        sendResponce(500, "Wrong query prepare statement", null);
        exit(1);
    }

    if(!$query->bind_param("ii", $buyer, $gid)){
        sendResponce(500, "Wrong query parameters", null);
        exit(1);
    }

    if(!$query->execute()){
        sendResponce(500, "Database Error-" . $query->errno . ": could not update the buyer", null);
        exit(1);
    }

    sendResponce(200, "Buyer updated successfully", null);

}else if(isset($item) & isset($uid) & isset($price)){

    $query = $db -> prepare("SELECT ITEMS,PRICE FROM $DB_table_groups WHERE GID=?");
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
        sendResponce(500, "Invalid group id", null);
        exit(1);
    }

    $result = $result->fetch_assoc();
    $result['ITEMS'] = $result['ITEMS'] . $DB_separator_1 . $uid . $DB_separator_2 . $item;
    $result['PRICE'] += $price;

    $query = $db -> prepare("UPDATE $DB_table_groups SET ITEMS=?, PRICE=? WHERE GID=?");
    if(!$query){
        sendResponce(500, "Wrong query prepare statement", null);
        exit(1);
    }

    if(!$query->bind_param("sii", $result['ITEMS'], $result['PRICE'], $gid)){
        sendResponce(500, "Wrong query parameters", null);
        exit(1);
    }

    if(!$query->execute()){
        sendResponce(500, "Database Error-" . $query->errno . ": could not get the details", null);
        exit(1);
    }

    sendResponce(200, "Item added successfully", null);

}else if(isset($address)){



}

?>