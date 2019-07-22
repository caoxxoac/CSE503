<!doctype html>
<html lang="en">
    <head>
        <title>Sharing File</title>
        <link rel="stylesheet" type="text/css" href="simpleLook.css" />
    </head>
</html>
<?php
session_start();
$username = $_SESSION["username"];
$shareTo = $_POST["shareUser"];
$shareFile = $_POST["share"];

// keeps tracking whether the user logged in before he goes to this page
if ($username == NULL) {
    header("Location: index.html");
}

// if the user we want to share is empty(we didn't input it), display the message
// and go back to the file management page
if ($shareTo == NULL){
    echo "<p>You cannot share a file to no one! Going back to file management page in 4 seconds</p>";
    header("refresh: 4; url=fileManagement.php");
    exit;
}

// if the file name user inputs is empty, display the message and go back to the file management page
if ($shareFile == NULL){
    echo "<p>You cannot share nothing to someone! Going back to file management page in 4 seconds</p>";
    header("refresh: 4; url=fileManagement.php");
    exit;
}

// make sure the user we are sharing to exist
$openUser = fopen("/home/xcao22/file/users.txt", "r") or exit("Open the file unsuccessfully!!!");
$userExist = false;
while (!feof($openUser)) {
    $eachLine = fgets($openUser);
    if (trim($eachLine, "\n") == $shareTo) {
        $userExist = true;
    }
}

// go back to the file management page if the user does not exist
if (!$userExist) {
    echo "<p>The user you share to does not exist! Going back to file management page in 4 seconds</p>";
    header("refresh: 4; url=fileManagement.php");
    exit;
}

// check if the file exist, and share the file if it does. Otherwise, display the message and
// go back to file management page
$filePath = "/home/xcao22/users/".$username."/";
$fileExist = false;
if (file_exists($filePath)){
    if ($fileList = opendir($filePath)){
        while (($file = readdir($fileList)) !== false){
            if ($file == $shareFile){
                $fileExist = true;
            }
        }
        closedir($fileList);
    }
}

if ($fileExist){
    $shareToPath = "/home/xcao22/users/".$shareTo."/";
    // if the user we share to does not have a directory in the system, we create one for him
    if (!file_exists($shareToPath)){
        mkdir($shareToPath, 0777, true);
    }

    // we want to make sure the user we share to does not have the same file
    if (file_exists($shareToPath.basename($shareFile))){
      echo "<p>The user already had the file, try to share other files!
      Going back to file management page in 4 seconds</p>";
      header("refresh: 4; url=fileManagement.php");
      exit;
    }
    // Otherwise, just share the file
    else {
      if (copy($filePath.basename($shareFile), $shareToPath.basename($shareFile))){
          echo "<p>share the file ".$shareFile." to user ".$shareTo." successfully!
          Going back to file management page in 4 seconds</p>";
          header("refresh: 4; url=fileManagement.php");
          exit;
      }
      else{
          echo "<p>share the file ".$shareFile." to user ".$shareTo." unsuccessfully!
          Going back to file management page in 4 seconds</p>";
          header("refresh: 4; url=fileManagement.php");
          exit;
      }
    }
}
else{
    echo "<p>The file you share does not exist! Going back to file management page in 4 seconds</p>";
    header("refresh: 4; url=fileManagement.php");
    exit;
}
?>
