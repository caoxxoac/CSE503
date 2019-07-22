<!DOCTYPE html>
<?php
session_start();
$userid = $_SESSION["userid"];
if ($userid == NULL){
  header("Location: index.html");
  exit;
}

if (isset($_SESSION["bgcolor"])){
  $backgroundColor = $_SESSION["bgcolor"];
}
else {
  $backgroundColor = "#FFFFFF";
}
?>

<html lang="en">
<head>
  <title>Post a Story</title>
</head>

<body style="background: <?php echo $backgroundColor; ?>">
  <form name="create" action="storyCreate.php" method="POST">
    <label>Story Title</label><br><input name="storytitle"/><br>
    <label>Associated Link</label><br><input name="storylink"/><br>
    <label>Story Content</label><br>
    <textarea name="storycontent" rows="5" cols="40"></textarea><br><br>
    <input type="submit" name="storycreate" value="Post Story"/>
  </form>
  <hr/>
  <form name="showAll" action="showAllStory.php" method="POST">
    <input type="submit" name="showAllStory" value="Home"/>
  </form>
  <br>
  <form name="show" action="showStory.php" method="POST">
    <input type="submit" name="showStory" value="My Account"/>
  </form>
  <br>
  <form name="logout" action="logout.php" method="POST">
    <input type="submit" name="logout" value="Log Out"/>
  </form>
</body>
</html>
