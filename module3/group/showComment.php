<?php
session_start();
/*
if ($_SESSION["loggedin"] != 0 && !isset($_SESSION["userid"])){
  header("Location: index.html");
  exit;
}
*/
$storyid = $_POST["storyid"];
$storyTitle = $_POST["storytitle"];
$storyContent = $_POST["storycontent"];

if (isset($_SESSION["bgcolor"])){
  $backgroundColor = $_SESSION["bgcolor"];
}
else {
  $backgroundColor = "#FFFFFF";
}
echo "<body style='background: $backgroundColor;'>";

require "database.php";

$stmt = $mysqli->prepare("SELECT comment_content, comment_id FROM comments WHERE story_id=?");
if (!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
}
$stmt->bind_param("i", $storyid);
$stmt->execute();
$stmt->bind_result($commentContent, $commentID);
echo "<label>Story Title: $storyTitle</label>";
echo "<p>$storyContent</p>";

echo "<hr>";
while ($row = $stmt->fetch()){
  echo "<label>Comment: </label>";
  echo "<p>$commentContent</p>";
  echo "<form name='edit' action='commentEdit.php' method='POST'>
  <input type='hidden' name='commentid' value='$commentID'/>
  <input type='submit' name='edit' value='Edit'/>
  </form>";
  echo "<form name='delete' action='commentDelete.php' method='POST'>
  <input type='hidden' name='commentid' value='$commentID'/>
  <input type='submit' name='delete' value='Delete'/>
  </form><br>";
  echo "<hr>";
}
echo "<form name='add' action='commentAdd.php' method='POST'>
<input type='hidden' name='storyid' value='$storyid'/>
<input type='submit' name='addcomment' value='Add Comment'/>
</form>";
echo "<form action='showAllStory.php'>
<input type='submit' value='Home'/>
</form>";
echo "<form name='logout' action='logout.php' method='POST'>
<input type='submit' name='logout' value='Log Out'/>
</form>";
$stmt->close();
?>
