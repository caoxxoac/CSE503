<?php
session_start();
$username = $_SESSION["username"];

// the user can only be added by someone who already have the account
if ($username == NULL){
    header("Location: index.html");
}

// once the user is added to the system, then the user is able to login and upload the file
$newUserName = $_POST["addUser"];
$userAddable = true;
if ($newUserName != NULL){
    if (preg_match("/^[\w_\.\-]+$/", $newUserName)) {
        $filePath = "/home/xcao22/file/users.txt";
        $openFile = fopen($filePath, "r+") or exit("open the file unsuccessfully!");
        while (!feof($openFile)){
            $eachLine = fgets($openFile);
            $eachLine = trim($eachLine, "\n");
            if ($eachLine == $newUserName){
                $userAddable = false;
            }
        }

        if ($userAddable){
            fwrite($openFile, "\n".$newUserName);
            fclose($openFile);
            echo "Add the new user successfully! Going to file management page in 2 seconds";
            header("refresh: 2; url=fileManagement.php");
            exit;
        }
        else{
            echo "The username you enter already exist. Try other username";
            exit;
        }
    }
    else{
        echo "Invalid input for username";
        exit;
    }
}
?>