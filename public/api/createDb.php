<?php
require_once 'dbInfo.php';
require_once 'helperFunctions.php';

$db = new mysqli($DB_host, $DB_user, $DB_password);

//creating database
$query = "CREATE DATABASE IF NOT EXISTS " . $DB_database;
if($db -> query($query)) {
    $db ->connect($DB_host, $DB_user, $DB_password, $DB_database);
} else {
    sendResponce(500, "Database Error-" . $db->errno . ": main database is not created", null);
    return;
}

//creating user table
$query = "CREATE TABLE IF NOT EXISTS " . $DB_table_users . " (UID INT PRIMARY KEY AUTO_INCREMENT, UNAME VARCHAR(70) NOT NULL, FNAME VARCHAR(35), LNAME VARCHAR(35), EMAIL VARCHAR(256) NOT NULL, MOBILE VARCHAR(15) NOT NULL, PINCODE VARCHAR(11), COLLAGE VARCHAR(200), GROUPS VARCHAR(200) )";
if($db -> query($query)) {
} else {
    sendResponce(500, "Database Error-" . $db->errno . ": user's database is not created", null);
    return;
}

//creating groups table
$query = "CREATE TABLE IF NOT EXISTS " . $DB_table_groups . " (GID INT PRIMARY KEY AUTO_INCREMENT, USERS VARCHAR(200), MESSAGES MEDIUMTEXT, ADDRESS VARCHAR(100))";
if($db -> query($query)) {
} else {
    sendResponce(500, "Database Error-" . $db->errno . ": group's database is not created", null);
    return;
}

sendResponce(200, "ok", null);
?>