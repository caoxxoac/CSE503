<!doctype html>
<html>
<head>
  <title>Login to Our File Sharing Site</title>
</head>

<body>
  <form name="input" action="login.php" method="POST">
    Username: <br><input type="text" name="username" value="<?php if(isset($_POST["username"])) echo $_POST["username"] ?>"/><br>
    <input type="submit" name="submit" value="Login"/>
  </form>


  <?php
    session_start();
    if (isset($_POST["submit"])) {
        $username = $_POST["username"];
        if (preg_match("/^[\w_\.\-]+$/", $username)) {
            $openFile = fopen("/home/xcao22/file/users.txt", "r") or exit("Open the file unsuccessfully!!!");
            // if the file contains the username, then go to the file management page
            while (!feof($openFile)) {
                // assume all username will be stored line by line in the file
                $eachLine = fgets($openFile);
                if (trim($eachLine, "\n") == $username) {
                    $_SESSION["username"] = $username;
                    header("Location: fileManagement.php");
                    fclose($openFile);
                    exit;
                }
            }
            echo "cannot find this user";
            exit;
        }
        else {
            echo "Invalid input for username!";
            exit;
        }
    }
    ?>
  
</body>
</html>
