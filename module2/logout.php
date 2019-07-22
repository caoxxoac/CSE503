<?php
session_start();
$username = $_SESSION["username"];

if ($username == NULL){
    header("Location: index.html");
}

session_destroy();
echo "Log out successfully, going to home page after 2 seconds";
header("refresh: 2; url = index.html");
?>