<!doctype html>
<html lang="en">
    <head>
        <title>Deleting File</title>
        <link rel="stylesheet" type="text/css" href="simpleLook.css" />
    </head>
</html>

<?php
session_start();

// keep tracking the user who logged in, and send people back to the login page if
// someone try to access the page without logging in
$username = $_SESSION["username"];
if ($username == NULL){
    header("Location: index.html");
}
// get the name of the file we want to delete, and refreshing the
// page if user enters nothing for the delete file
$fileName = $_POST["deleteFileName"];
if ($fileName == NULL){
    header("Location: fileManagement.php");
}

// if the file can be found, unlink the file
$filePath = "/home/xcao22/users/".$username."/".$fileName;
if (file_exists($filePath)){
    if (unlink($filePath)){
        echo "<p>File has been deleted successfully. Going back to file managment page in 4 seconds</p>";
        header("refresh: 4; url=fileManagement.php");
        exit;
    }
    else {
        echo "<p>Unable to delete the file. Try again. Going back to file management page in 4 seconds</p>";
        header("refresh: 4; url=fileManagement.php");
        exit;
    }
}
else{
    echo "<p>The file does not exist! Going back to file management page in 4 seconds</p>";
    header("refresh: 4; url=fileManagement.php");
    exit;
}
?>
