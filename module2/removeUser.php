<?php
session_start();
$username = $_SESSION["username"];
if ($username == NULL) {
    header("Location: index.html");
}

$removeUser = $_POST["removeUser"];
if ($removeUser == NULL) {
    echo "You cannot remove no one. Going back to file management page in 4 seconds";
    header("refresh: 4; url=fileManagement.php");
    exit;
}

if ($removeUser == $username){
    echo "You cannot remove yourself. Going back to file management page in 4 seconds";
    header("refresh: 4; url=fileManagement.php");
    exit;
}

// my rule is that old user can delte the newer user, but newer user cannot delete the old user
$userListPath = "/home/xcao22/file/users.txt";
$foundUserFirst = false;
$foundRemoveUserFirst = false;
$foundRemoveUser = false;
$openFile = fopen($userListPath, "r+") or exit("Open the file unsuccessfully!");
while (!feof($openFile)){
    $eachLine = fgets($openFile);
    $eachLine = trim($eachLine, "\n");
    if ($eachLine == $username && !$foundRemoveUserFirst) {
        $foundUserFirst = true;
    }
    elseif ($eachLine == $removeUser && !$foundUserFirst) {
        $foundRemoveUser = true;
        $foundRemoveUserFirst = true;
    }
    else if ($eachLine == $removeUser && $foundUserFirst){
        $foundRemoveUser = true;
    }
}
if (!$foundRemoveUser){
    echo "You cannot remove someone who does not exist! Going back to file management page in 4 seconds";
    header("refresh: 4; url=fileManagement.php");
    exit;
}
else {
    $usersContent = file_get_contents($userListPath);
    $usersContent = str_replace($removeUser."\n", "", $usersContent);
    file_put_contents($userListPath, $usersContent);
}
if ($foundUserFirst) {
    $removePath = "/home/xcao22/users/".$removeUser;
    if (is_dir($removePath)){
        $fileList = opendir($removePath);
        while (false !== ($file = readdir($fileList))){
            if ($file != "." && $file != ".." && $file != NULL){
                $tempPath = $removePath.basename($file);
                unlink($tempPath);
            }       
        }
        rmdir($removePath);
        closedir($fileList);
    }
    echo "You have removed $removeUser successfully";
    exit; 
}
else {
    echo "You cannot remove someone who has more power(older user than you). Going back to file management page in 4 seconds";
    header("refresh: 4; url=fileManagement.php");
    exit;
}

?>