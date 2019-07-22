<!doctype html>
<html lang="en">
    <head>
        <title>Removing User</title>
        <link rel="stylesheet" type="text/css" href="simpleLook.css" />
    </head>
</html>
<?php
session_start();
$username = $_SESSION["username"];
if ($username == NULL) {
    header("Location: index.html");
}

$removeUser = $_POST["removeUser"];
if ($removeUser == NULL) {
    echo "<p>You cannot remove no one. Going back to file management page in 4 seconds</p>";
    header("refresh: 4; url=fileManagement.php");
    exit;
}

if ($removeUser == $username){
    echo "<p>You cannot remove yourself. Going back to file management page in 4 seconds</p>";
    header("refresh: 4; url=fileManagement.php");
    exit;
}

// my rule is that old user can delte the newer user, but newer user cannot delete the old user
$userListPath = "/home/xcao22/file/users.txt";
// this variable is used to track if the current user is found in the users.txt file first
$foundUserFirst = false;
// this variable is used to track if the user we want to remove is found in the users.txt file first
$foundRemoveUserFirst = false;
// this variable is used to track if the user we want to remove exists
$foundRemoveUser = false;
$openFile = fopen($userListPath, "r+") or exit("Open the file unsuccessfully!");

// go through each line of the file, and assign the value to all variables wo created above.
// So that we can track who has bigger power(old user has more power than newer user,
// old user refers to the user that has the least line number in the users.txt file)
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
    echo "<p>You cannot remove someone who does not exist! Going back to file management page in 4 seconds</p>";
    header("refresh: 4; url=fileManagement.php");
    exit;
}
else {
    $usersContent = file_get_contents($userListPath);
    // replace the user we want to remove with an empty string.
    // Since the username is stored line by line, so the trick is that
    // if the user we want to remove have a new line character after it's name,
    // we replace the the user's name and the new line character whole thing with an empty string
    // otherwise, just replace the user's name with an empty string
    if ($usersContent = str_replace($removeUser."\n", "", $usersContent));
    else {
      $usersContent = str_replace($removeUser, "", $usersContent);
    }
    file_put_contents($userListPath, $usersContent);
}

// current user only be able to remove other users if he has bigger power,
// which means the current user has to be found first we when go through each line
// of users.txt file
if ($foundUserFirst) {
    $removePath = "/home/xcao22/users/".$removeUser."/";
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
    echo "<p>You have removed $removeUser successfully. Going back to file management page in 4 seconds</p>";
    header("refresh: 4; url=fileManagement.php");
    exit;
}

// Otherwise, current user cannot remove other users from the system
else {
    echo "<p>You cannot remove someone who has more power(older user than you). Going back to file management page in 4 seconds</p>";
    header("refresh: 4; url=fileManagement.php");
    exit;
}

?>
