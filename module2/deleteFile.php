<?php
session_start();

$username = $_SESSION["username"];
if ($username == NULL){
    header("Location: index.html");
}

$fileName = $_POST["deleteFileName"];
$filePath = "/home/xcao22/users/".$username."/".$fileName;

if (file_exists($filePath)){
    if (unlink($filePath)){
        echo "File has been deleted successfully. Going back to file managment page in 2 seconds";
        header("refresh: 2; url=fileManagement.php");
    }
    else {
        echo "Unable to delete the file. Try again";
        exit;
    }
}
else{
    echo "The file does not exist! Going back to file management page in 2 seconds";
    header("refresh: 2; url=fileManagement.php");
}
?>