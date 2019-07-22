<!doctype html>
<html lang="en">
    <head>
        <title>Adding User</title>
        <link rel="stylesheet" type="text/css" href="simpleLook.css" />
    </head>
</html>
<?php
/* adding a new user to the system will not automatically create a directory for
under their name, but their name will be added to the users.txt file. new user's
directory will be created after the user uploaded something */


session_start();
$username = $_SESSION["username"];

// the user can only be added by someone who already have the account
// and logged into the system. Otherwise, send them back to login page
if ($username == NULL){
    header("Location: index.html");
}

// once the user is added to the system, then the user is able to login and upload the file

// check if the user can be added to the system by checking valida input of username
// and check if the user is already in our users.txt file (user already exists)
$newUserName = $_POST["addUser"];
$userAddable = true;
if ($newUserName != NULL){
    if (preg_match("/^[\w_\.\-]+$/", $newUserName)) {
        $filePath = "/home/xcao22/file/users.txt";
        $openFile = fopen($filePath, "r+") or exit("open the file unsuccessfully!");
        while (!feof($openFile)){
            $eachLine = fgets($openFile);
            $eachLine = trim($eachLine, "\n");
            // set $userAddable to false if the user already exists in the system
            if ($eachLine == $newUserName){
                $userAddable = false;
            }
        }

        if ($userAddable){
            fwrite($openFile, $newUserName."\n");
            fclose($openFile);
            echo "<p>Add the new user successfully! Going to file management page in 4 seconds</p>";
            header("refresh: 4; url=fileManagement.php");
            exit;
        }
        else{
            echo "<p>The username you enter already exist. Try other username.
            Going back to file management page in 4 seconds</p>";
            header("refresh: 4; url=fileManagement.php");
            exit;
        }
    }
    else{
        echo "<p>Invalid input for username. Going back to file management
         page in 4 seconds</p>";
         header("refresh: 4; url=fileManagement.php");
        exit;
    }
}
else {
    header("Location: fileManagement.php");
}
?>
