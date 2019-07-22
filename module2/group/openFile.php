<?php
session_start();
$username = $_SESSION["username"];
$fileName = $_POST["openFileName"];
$getAction = $_POST["submit"];
$open = false;
$download = false;
if ($getAction == "Open"){
  $open = true;
  $download = false;
}
else if($getAction == "Download"){
  $open = false;
  $download = true;
}

// go back to the log in page if the user has not logged in yet
if ($username == NULL){
    header("Location: index.html");
}

// refresh the file management page if user enters nothing for the file we want to open
if ($fileName == NULL){
    header("Location: fileManagement.php");
}

// open or download the file if the file can be found
if ($open){
  $filePath = "/home/xcao22/users/".$username."/".$fileName;
  if (file_exists($filePath)){
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $fileInfo = $finfo->file($filePath);
    header("Content-Type: ".$fileInfo);
    readfile($filePath);
  }
  else {
    echo "<p>The file does not exist!! going back to the file management page in 4 seconds</p>";
    header("refresh: 4; url=fileManagement.php");
    exit;
  }
}
else if ($download){
  $downloadName = $fileName;
  if ($downloadName == NULL){
    echo "<p>You cannot download an empty file!! going back to the file management page in 4 seconds</p>";
    header("refresh: 4; url=fileManagement.php");
    exit;
  }

  $downloadFilePath = "/home/xcao22/users/".$username."/".$downloadName;
  // check if the file we download exists
  if (file_exists($downloadFilePath)){
    header("Content-Description: File Transfer");
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=".basename($downloadFilePath));
    header("Content-Transfer-Encoding: binary");
    readfile($downloadFilePath);
  }
  else{
    echo "<p>The file does not exist!! going back to the file management page in 4 seconds</p>";
    header("refresh: 4; url=fileManagement.php");
    exit;
  }
}
else {
    echo "<p>We have no access to this file or this file does not exist. Going back to file management page in 4 seconds</p>";
    header("refresh: 4; url=fileManagement.php");
    exit;
}
?>
