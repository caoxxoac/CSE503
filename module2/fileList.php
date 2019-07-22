<!doctype html>
<?php
session_start();
$username = $_SESSION["username"];
if ($username == NULL) {
    header("Location: index.html");
}
?>
<html>
    <head>
        <title>All Files You Uploaded</title>
    </head>
    <body>
        <h3>File List</h3>
        <form action="fileList.php" method="POST">
            <input type="submit" name="back" value="Back"/>
        </form>

    <?php
    $filePath = "/home/xcao22/users/".$username;
    if (is_dir($filePath)){
        $fileList = opendir($filePath);
        while (false !== ($file= readdir($fileList))){
            // we dont wan't the "." and ".." file printed out
            if ($file != "." && $file != ".."){
                echo "$file<br>";
            }
        }
        closedir($fileList);
    }
    else {
        echo "The directory does not exist or You have no access to this directory. Going back in 4 seconds";
        header("refresh: 4; url=fileManagement.php");
        exit;
    }
    if (isset($_POST["back"])){
        header("Location: fileManagement.php");
        exit;
    }
    ?>
    </body>
</html>