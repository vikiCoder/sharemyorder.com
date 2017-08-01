<?php
require_once 'dbInfo.php';
require_once 'helperFunctions.php';

$db = new mysqli($DB_host, $DB_user, $DB_password);
if($db -> connect_error){
    sendResponce(500, "Connect Error-" . $db->connect_errno . ": could not connect to database", null);
    $db -> close();
    return;
}

//creating database
$query = "CREATE DATABASE IF NOT EXISTS $DB_database";
if($db -> query($query)) {
    $db ->connect($DB_host, $DB_user, $DB_password, $DB_database);
    if($db -> connect_error){
        sendResponce(500, "Connect Error-" . $db->connect_errno . ": could not connect to database", null);
        $db -> close();
        return;
    }
} else {
    sendResponce(500, "Database Error-" . $db->errno . ": main database is not created", null);
    $db -> close();
    return;
}

//creating user table
$query = "CREATE TABLE IF NOT EXISTS $DB_table_users (UID INT PRIMARY KEY AUTO_INCREMENT, UNAME VARCHAR(70) NOT NULL, FNAME VARCHAR(35), LNAME VARCHAR(35), EMAIL VARCHAR(256) NOT NULL, PASSWORD VARCHAR(128) NOT NULL, MOBILE VARCHAR(15) NOT NULL, PINCODE VARCHAR(11), COLLAGE VARCHAR(200), GROUPS VARCHAR(200) )";
if(!$db -> query($query)) {
    sendResponce(500, "Database Error-" . $db->errno . ": user's database is not created", null);
    $db -> close();
    return;
}

//creating groups table
$query = "CREATE TABLE IF NOT EXISTS $DB_table_groups (GID INT PRIMARY KEY AUTO_INCREMENT, USERS VARCHAR(200), MESSAGES MEDIUMTEXT, ADDRESS VARCHAR(100))";
if(!$db -> query($query)) {
    sendResponce(500, "Database Error-" . $db->errno . ": group's database is not created", null);
    $db -> close();
    return;
}

sendResponce(200, "ok", null);
$db -> close();
?>