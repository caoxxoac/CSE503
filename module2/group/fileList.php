<!doctype html>
<?php
session_start();
$username = $_SESSION["username"];
if ($username == NULL) {
    header("Location: index.html");
}
?>
<html lang="en">
    <head>
        <title>All Files You Uploaded</title>
        <link rel="stylesheet" type="text/css" href="simpleLook.css" />
    </head>
    <body>
        <h3>File List</h3>
        <form action="fileList.php" method="POST">
            <input type="submit" name="back" value="Back"/>
        </form>

    <?php
    // if the user hasn't uploaded anything before, there will be no directory for
    // this user. If the user has a directory, we just open it, and get the name of
    // each file, but we don't want the file '.' and '..' displayed as our outcome
    $filePath = "/home/xcao22/users/".$username;
    if (is_dir($filePath)){
        $fileList = opendir($filePath);
        while (false !== ($file= readdir($fileList))){
            // we dont wan't the "." and ".." file printed out
            if ($file != "." && $file != ".."){
                echo "<p>$file</p>";
            }
        }
        closedir($fileList);

    }
    else {
        echo "<p>You haven't uploaded any file yet!</p>";
    }
    if (isset($_POST["back"])){
        header("Location: fileManagement.php");
        exit;
    }
    ?>
    </body>
</html>
