<!doctype html>
<?php 
session_start();
$username = $_SESSION["username"];
if ($username == NULL){
  header("Location: index.html");
}
?>
<html>
  <head>
    <title>Upload A File Here</title>
  </head>
  
  <body>
    <form action="fileManagement.php" method="POST" enctype="multipart/form-data">
      <input type="file" name="myFile"/>
      <input type="submit" name="upload" value="Upload"/>
    </form>
    <br>
    <form action="deleteFile.php" method="POST">
      Enter the file name you want to delete: 
      <input type="text" name="deleteFileName"/>
      <input type="submit" name="delete" value="Delete"/>
    </form>
    <br>
    <form action="openFile.php" method="POST">
      Enter the file name you want to open:
      <input type="text" name="openFileName"/>
      <input type="submit" name="open" value="Open"/>
    </form>

    <br><br>
    <h3>File List</h3>
    <form action="fileList.php" method="POST">
      <input type="submit" name="list" value="List Files"/>
    </form>

    <br>
    <form action="addUser.php" method="POST">
      Enter the username you want to add to the system: 
      <input type="text" name="addUser"/>
      <input type="submit" name="add" value="Add"/>
    </form>

    <br>
    <form action="removeUser.php" method="POST">
      Enter the username you want to remove from the system: 
      <input type="text" name="removeUser"/>
      <input type="submit" name="remove" value="Remove"/>
    </form>

    <br>
    <form action="shareFile.php" method="POST">
      Enter the file name you want to share:<input type="text" name="share"/>
      Enter the user name you want to share with:<input type="text" name="shareUser"/>
      <input type="submit" name="shareFile" value="Share"/> 
    </form>
    
    <br>
    <form action="logout.php" method="POST">
      <input type="submit" name="logout" value="Log Out"/>
    </form>



    <?php
    if (isset($_POST["upload"])){
      $theFile = basename($_FILES["myFile"]["name"]);
      $filePath = "/home/xcao22/users/".$username."/";

      // create a new directory if the user does not upload anything before
      if (!file_exists($filePath)){
        mkdir($filePath, 0777, true);
      }
      $filePath = $filePath.basename($theFile);

      // check if the file we want to upload exists
      if (file_exists($theFile)){
        echo "The file you upload already exists!";
        exit;
      }
      else {
        if (move_uploaded_file($_FILES["myFile"]["tmp_name"], $filePath)){
          echo "Upload Successfully!!!";
          exit;
        }
        else {
          echo "Upload Unsuccessfully!!!!!! Try again";
          exit;
        }
      }
    }
    ?>
  </body>
</html>