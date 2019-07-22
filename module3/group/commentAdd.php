<!DOCTYPE html>
<?php
session_start();
$storyid = $_POST["storyid"];
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
<html>
<head>
  <title>Add Comment</title>
</head>

<body style="background: <?php echo $backgroundColor; ?>">
<?php
if (isset($_POST["add"])){
  if (isset($userid)){
    $commentContent = $_POST["commentcontent"];

    require "database.php";
    $stmt = $mysqli->prepare("INSERT INTO comments (user_id, story_id, comment_content)
    VALUES (?, ?, ?)");
    if (!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
    }

    $stmt->bind_param("iis", $userid, $storyid, $commentContent);
    $stmt->execute();
    $stmt->close();
    echo "<p>You have commented successfully! Going back to story...</p><br>";
    header("refresh: 3; url=showAllStory.php");
    exit;
  }
  else{
    echo "<p>You have to log in first before you can add comments! Going back to story...</p>";
    header("refresh: 3; url=showAllStory.php");
  }
}
?>
<form name="add" action="commentAdd.php" method="POST">
  <label>Comment Area</label><br>
  <textarea name="commentcontent" rows="5" cols="40"></textarea><br>
  <input type="hidden" name="storyid" value="<?php echo $storyid; ?>"/>
  <input type="submit" name="add" value="Comment"/>
</form>
</body>
</html>
