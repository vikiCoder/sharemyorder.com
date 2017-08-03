<?php
require_once 'dbInfo.php';
require_once 'helperFunctions.php';

/*Required POST parameters
Must: uname, email, password, mobile
Optional: fname, lname, pincode, collage
*/

//$uname = "b";
//$email = "b@b";
//$password = "b";
//$mobile = "1";

if(sizeof($_POST)==0) {
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);
    extract($_POST);
}

$password = hash('sha512', $DB_salt + $password);

$db = new mysqli($DB_host, $DB_user, $DB_password, $DB_database);
if($db -> connect_error){
    sendResponce(500, "Connect Error-" . $db->connect_errno . ": could not connect to database", null);
    exit(1);
}

$query = $db -> prepare("INSERT INTO $DB_table_users (UNAME, FNAME, LNAME, EMAIL, PASSWORD, MOBILE, PINCODE, COLLAGE) VALUES (?,?,?,?,?,?,?,?)");
if(!$query){
    sendResponce(500, "Wrong query prepare statement", null);
    exit(1);
}

if(!$query -> bind_param('ssssssss', $uname, $fname, $lname, $email, $password, $mobile, $pincode, $collage)){
    sendResponce(500, "Wrong query parameters", null);
    exit(1);
}

if($query -> execute()){
    $data['UID'] = $db -> insert_id;
    sendResponce(200, "Registered successfully", $data);
}else{
    sendResponce(500, "Database Error-" . $query->errno . ": could not register the user", null);
    exit(1);
}

?>