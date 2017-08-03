<?php
require_once 'dbInfo.php';
require_once 'helperFunctions.php';

/*No POST parameters required*/

$db = new mysqli($DB_host, $DB_user, $DB_password, $DB_database);
if($db -> connect_error){
    sendResponce(500, "Connect Error-" . $db->connect_errno . ": could not connect to database", null);
    exit(1);
}

$query = $db -> prepare("INSERT INTO $DB_table_groups (USERS) VALUES (?)");
if(!$query){
    sendResponce(500, "Wrong query prepare statement", null);
    exit(1);
}

$emptyString = "";
if(!$query -> bind_param("s", $emptyString)){
    sendResponce(500, "Wrong query parameters", null);
    exit(1);
}

if($query -> execute()){
    $data['GID'] = $db -> insert_id;
    sendResponce(200, "Group successfully created", $data);
}else{
    sendResponce(500, "Database Error-" . $query->errno . ": could not register the user", null);
    exit(1);
}

?>