<?php
require_once 'dbInfo.php';
require_once 'helperFunctions.php';

/*Required POST parameters
uid / email, password
*/

//$uname = "viki";
//$fname = "Premang";
//$lname = "Vikani";
//$email = "premangvikani@gmail.com";
//$password = "123";
//$mobile = "9408231332";
//$pincode = "360005";
//$collage = "DAIICT";
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

if(isset($uid)) {

    $query = $db->prepare("SELECT UID,UNAME,FNAME,LNAME,EMAIL,MOBILE,PINCODE,COLLAGE,GROUPS FROM $DB_table_users WHERE UID=?");
    if(!$query){
        sendResponce(500, "Wrong query prepare statement", null);
        exit(1);
    }

    if(!$query -> bind_param("i", $uid)){
        sendResponce(500, "Wrong query parameters", null);
        exit(1);
    }

    if(!$query -> execute()){
        sendResponce(500, "Database Error-" . $query->errno . ": could not get the user", null);
        exit(1);
    }

    $result = $query -> get_result();
    if($result->num_rows != 1){
        sendResponce(500, "Invalid login details", null);
        exit(1);
    }else{
        $data = $result->fetch_assoc();
        $data['GROUPS'] = getArrayFromString($data['GROUPS'], $DB_seperator_1);
        sendResponce(200, "ok", $data);
    }

}else{
    $password = hash('sha512', $DB_salt + $password);

    $query = $db->prepare("SELECT UID,UNAME,FNAME,LNAME,EMAIL,MOBILE,PINCODE,COLLAGE,GROUPS FROM $DB_table_users WHERE EMAIL=? AND PASSWORD=?");
    if(!$query){
        sendResponce(500, "Wrong query prepare statement", null);
        exit(1);
    }

    if(!$query -> bind_param("ss", $email, $password)){
        sendResponce(500, "Wrong query parameters", null);
        exit(1);
    }

    if(!$query -> execute()){
        sendResponce(500, "Database Error-" . $query->errno . ": could not get the user", null);
        exit(1);
    }

    $result = $query -> get_result();
    if($result->num_rows != 1){
        sendResponce(500, "Invalid login details", null);
        exit(1);
    }else{
        $data = $result->fetch_assoc();
        $data['GROUPS'] = getArrayFromString($data['GROUPS'], $DB_seperator_1);
        sendResponce(200, "ok", $data);
    }

}


?>