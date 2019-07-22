<!DOCTYPE html>
<html lang="en">
	<head>
		<title>News - My Account</title>
	</head>
</html>

<?php
session_start();
$userid = $_SESSION["userid"];
if ($userid == NULL){
  header("Location: index.html");
}

if (isset($_SESSION["bgcolor"])){
  $backgroundColor = $_SESSION["bgcolor"];
}
else {
  $backgroundColor = "#FFFFFF";
}
echo "<body style='background: $backgroundColor;'></body>";

require "database.php";

$stmt = $mysqli->prepare("SELECT story_id, story_title, story_content, link_content FROM stories
WHERE user_id=?");
//$stmt1 = $mysqli->prepare("SELECT link_content FROM links WHERE story_id=?");
if (!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
}
$stmt->bind_param("i", $userid);
$stmt->execute();
$stmt->bind_result($storyid, $storyTitle, $storyContent, $linkContent);
  echo "<h1>My Account</h1><br/>";

  echo "<form action='story.php'>
<input type='submit' value='Post New Story'/>
</form>";
echo "<form action='showAllStory.php'>
<input type='submit' value='Home'/>
</form>";
echo "<form name='changepw' action='changePassword.php' method='POST'>
<input type='submit' name='changepw' value='Change Password'/>
</form>";
echo "<form name='logout' action='logout.php' method='POST'>
<input type='submit' name='logout' value='Log Out'/>
</form><br/>";

while ($row = $stmt->fetch()){
  echo "<hr/>";
  echo "<h3>$storyTitle</h3>";
  echo "<p>$storyContent</p>";
  echo "<p><a href='http://$linkContent' target='_blank'>Associated link: '$linkContent'</a></p>";
  echo "<form name='edit' action='storyEdit.php' method='POST'>
  <input type='hidden' name='storyid' value='$storyid'/>
  <input type='hidden' name='storytitle' value='$storyTitle'/>
  <input type='hidden' name='storycontent' value='$storyContent'/>
	<input type='hidden' name='linkcontent' value='$linkContent'/>
  <input type='submit' name='edit' value='Edit'/>
  </form>";
  echo "<form name='delete' action='storyDelete.php' method='POST'>
  <input type='hidden' name='storyid' value='$storyid'/>
  <input type='hidden' name='storytitle' value='$storyTitle'/>
  <input type='hidden' name='storycontent' value='$storyContent'/>
  <input type='submit' name='delete' value='Delete'/>
  </form><br>";
}

$stmt->close();

?>
