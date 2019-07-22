<!doctype html>
<html lang="en">
    <head>
        <title>Log out</title>
        <link rel="stylesheet" type="text/css" href="simpleLook.css" />
    </head>
</html>
<?php
session_start();
$username = $_SESSION["username"];

// we do not want to log out anyone who has not logged in yet
if ($username == NULL){
    header("Location: index.html");
}

// destroy the session, so that the user truly logs out and go back to the log in page
session_destroy();
echo "<p>Log out successfully, going to home page after 2 seconds</p>";
header("refresh: 2; url = index.html");
?>
