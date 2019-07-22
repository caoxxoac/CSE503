<?php
session_start();
$username = $_SESSION["username"];
$fileName = $_POST["openFileName"];
$canOpen = true;
if ($username == NULL){
    header("Location: index.html");
}

if ($fileName == NULL){
    header("Location: fileManagement.php");
}

if ($canOpen){
    $filePath = "/home/xcao22/users".$fileName;
    if (file_exists($filePath)){
        $finfo = finfo_open();
        $fileInfo = finfo_file($finfo, $filePath, FILEINFO_MIME);
        finfo_close($finfo);

        readfile($filePath);
    }
    else {
        echo "We have no access to this file or this file does not exist";
        header("refresh: 2; url=fileManagement.php"); 
    }
}
else{
    $downloadName = $_POST["downloadAFile"];
    if ($downloadName == NULL){
        echo "You cannot download an empty file!! going back to the file management page in 2 seconds";
        header("refresh: 2; url=fileManagement.php");
    }
    ///home/xcao22/users/
    $downloadFilePath = "/home/xcao22".$downloadName;
    // check if the file we download exists
    if (file_exists($downloadFilePath)){
        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".basename($downloadFilePath));
        header("Content-Transfer-Encoding: binary");
        readfile($downloadFilePath);
    }
    else{
        echo "The file does not exist!! going back to the file management page in 2 seconds";
        header("refresh: 2; url=fileManagement.php");
    }
}
?>