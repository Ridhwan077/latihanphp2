<?php
session_start(); 
session_destroy(); 

// arahkan kembali ke halaman login
header("Location: index.php");
exit();
?>
