<?php
$servername = "localhost";
$username = "lester1";
$password = "yabukinako14";
$dbname = "cms_db";
 

$mysqli = new mysqli($servername, $username, $password, $dbname);
 
// Check connection
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
?>