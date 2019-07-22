<!doctype html>
<?php
session_start();
$username = $_SESSION["username"];
if ($username == NULL){
  header("Location: index.html");
}
?>
<html lang="en">
  <head>
    <title>Upload A File Here</title>
    <link rel="stylesheet" type="text/css" href="fileManageFormat.css" />
  </head>

  <body>
    <form action="fileManagement.php" method="POST" enctype="multipart/form-data">
      <input type="file" name="myFile"/>
      <input type="submit" name="upload" value="Upload"/>
    </form>
    <br>
    <h2>File List</h2>
    <form action="fileList.php" method="POST">
      <input type="submit" name="list" value="List Files"/>
    </form>
    <br>

    <form action="deleteFile.php" method="POST">
      <label>
      Enter the file name you want to delete:
      </label>
      <input type="text" name="deleteFileName"/>
      <input type="submit" name="delete" value="Delete"/>
    </form>
    <br>
    <form action="openFile.php" method="POST">
      <label>
      Enter the file name you want to open/download:
      </label>
      <input type="text" name="openFileName"/>
      <input type="submit" name="submit" value="Open"/>
      <input type="submit" name="submit" value="Download"/>
    </form>

    <br>
    <form action="addUser.php" method="POST">
      <label>
      Enter the username you want to add to the system:
      </label>
      <input type="text" name="addUser"/>
      <input type="submit" name="add" value="Add"/>
    </form>

    <br>
    <form action="removeUser.php" method="POST">
      <label>
      Enter the username you want to remove from the system:
      </label>
      <input type="text" name="removeUser"/>
      <input type="submit" name="remove" value="Remove"/>
    </form>

    <br>
    <form action="shareFile.php" method="POST">
      <label>
      Share File (file name)
      </label> <input type="text" name="share"/>
      <label>
      To (user name)
      </label><input type="text" name="shareUser"/>
      <input type="submit" name="shareFile" value="Share"/>
    </form>

    <br>
    <form action="logout.php" method="POST">
      <input type="submit" name="logout" value="Log Out"/>
    </form>



    <?php
    // if the upload button is clicked, we will try to upload a file here
    if (isset($_POST["upload"])){
      $theFile = basename($_FILES["myFile"]["name"]);
      $filePath = "/home/xcao22/users/".$username."/";

      if ($theFile == NULL){
        echo "<p>Choose a file before you upload it</p>";
        exit;
      }

      // since a new user does not have it's own directory in the system, so we want to
      // create a new directory for the user if he does not upload anything before
      if (!file_exists($filePath)){
        mkdir($filePath, 0777, true);
      }

      // the file path we want the file stored at
      $filePath = $filePath.basename($theFile);
      // check if the file we want to upload exists
      if (file_exists($filePath)){
        echo "<p>The file you upload already exists!</p>";
        exit;
      }
      else {
        if (move_uploaded_file($_FILES["myFile"]["tmp_name"], $filePath)){
          echo "<p>Upload Successfully!!!</p>";
          exit;
        }
        else {
          echo "<p>Upload Unsuccessfully!!!!!! Try again</p>";
          exit;
        }
      }
    }
    ?>
  </body>
</html>
