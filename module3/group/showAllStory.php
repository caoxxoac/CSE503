<!DOCTYPE html>
<html lang="en">
	<head>
		<title>News - Home</title>
	</head>
</html>

<?php
session_start();
$_SESSION["loggedin"] = 1;
if (!isset($_SESSION["userid"])){
  $_SESSION["loggedin"] = 0;
}
if (isset($_SESSION["bgcolor"])){
  $backgroundColor = $_SESSION["bgcolor"];
}
else {
  $backgroundColor = "#FFFFFF";
}
echo "<body style='background: $backgroundColor;'>";

require "database.php";

$stmt = $mysqli->prepare("SELECT story_id, story_title, story_content, link_content FROM stories");
if (!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
}
$stmt->execute();
$stmt->bind_result($storyid, $storyTitle, $storyContent, $linkContent);

echo "<h1>Home</h1><br/>";

echo "<form action='story.php'>
<input type='submit' value='Post New Story'/>
</form>";
echo "<form action='showStory.php'>
<input type='submit' value='My Account'/>
</form>";
echo "<form name='background' action='backgroundColor.php' method='POST'>
    <input type='submit' name='bgchange' value='Change Background Color (random)'/>
  </form>";
echo "<form name='logout' action='logout.php' method='POST'>
<input type='submit' name='logout' value='Log Out'/>
</form>";

while ($row = $stmt->fetch()){
  echo "<hr/>";
  echo "<h3>$storyTitle</h3>";
  echo "<p>$storyContent</p>";
  echo "<p><a href='http://$linkContent' target='_blank'>Associated link: '$linkContent'</a></p>";
  echo "<form name='showcomment' action='showComment.php' method='POST'>
  <input type='hidden' name='storyid' value='$storyid'/>
  <input type='hidden' name='storytitle' value='$storyTitle'/>
  <input type='hidden' name='storycontent' value='$storyContent'/>
  <input type='submit' name='showcomment' value='View Comments'/>
  </form>";
  echo "<form name='download' action='downloadStory.php' method='POST'>
  <input type='hidden' name='storyid' value='$storyid'/>
  <input type='hidden' name='storytitle' value='$storyTitle'/>
  <input type='hidden' name='storycontent' value='$storyContent'/>
  <input type='submit' name='download' value='Download Story'/>
  </form>";
}
$stmt->close();
?>