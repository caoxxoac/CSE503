<!doctype html>
<html lang="en">
    <head>
        <title>Log out</title>
        <link rel="stylesheet" type="text/css" href="simpleLook.css" />
    </head>
</html>

<?php
session_start();
$userid = $_SESSION["userid"];
if ($userid == NULL){
  header("Location: index.html");
  exit;
}

// destroy the session, so that the user truly logs out and go back to the log in page
session_destroy();
echo "<p>Logged out successfully. Returning to Login page...</p>";
header("refresh: 2; url = index.html");
?>