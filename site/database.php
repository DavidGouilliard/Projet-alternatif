<?php

$host = "localhost";
$dbname = "intergalactiques";
$username = "root";
$password = "";

$mysqli = mysqli_connect($host, $username, $password, $dbname);
                     
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;

?>