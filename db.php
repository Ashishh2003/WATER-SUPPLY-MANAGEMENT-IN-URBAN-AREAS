<?php
$servername="localhost";
$username= "root";
$password= "root";
$dbname= "Water";
$conn = new mysqli($servername, $username, $password,$dbname);
if (!$conn) {
    echo"not connected";
}
?>