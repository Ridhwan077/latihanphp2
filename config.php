<?php
$host = "mysql";     
$user = "appuser";          
$pass = "password";              
$db   = "public_blog";       


$conn = new mysqli($host, $user, $pass, $db);


if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
?>
