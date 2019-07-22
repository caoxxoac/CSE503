<?php
session_start();
$username = $_SESSION["username"];
$shareTo = $_POST["shareUser"];
$shareFile = $_POST["shareFile"];

if ($username == NULL) {
    header("Location: index.html");
}

if ($shareTo == NULL){
    echo "You cannot share a file to no one";
    exit;
}

if ($shareFile == NULL){
    echo "You cannot share nothing to someone";
    exit;
}


$openUser = fopen("/home/xcao22/file/users.txt", "r") or exit("Open the file unsuccessfully!!!");
$userExist = false;
while (!feof($openUser)) {
    $eachLine = fgets($openUser);
    if (trim($eachLine, "") == $shareTo) {
        $userExist = true;
        exit;
    }
}
if ($shareTo){
    echo "The user you share to does not exist";
    exit;    
}

$filePath = "/home/xcao22/users/".$username;
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
    $newPath = "/home/xcao22/users/".$shareTo;
    if (move_uploaded_file($_FILES[$filePath.basename($shareFile)], $newPath)){
        echo "share the file ".$shareFile." to user ".$shareTo." successfully!";
        exit;
    }
    else{
        echo "share the file ".$shareFile." to user ".$shareTo." unsuccessfully!";
        exit;
    }
}

else{
    echo "The file you share does not exist";
    exit;
}
?>