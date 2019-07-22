<?php
session_start();
$userid = $_SESSION["userid"];
$storyTitle = $_POST["storytitle"];
$storyContent = $_POST["storycontent"];
$linkContent = $_POST["storylink"];

if ($userid == NULL){
  header("Location: index.html");
  exit;
}

if ($storyTitle == NULL || $storyContent == NULL || $linkContent == NULL){
  header("Location: story.php");
  exit;
}
if (isset($_SESSION["bgcolor"])){
  $backgroundColor = $_SESSION["bgcolor"];
}
else {
  $backgroundColor = "#FFFFFF";
}
echo "<body style='background: <?php echo $backgroundColor; ?>'>";


require "database.php";

$stmt = $mysqli->prepare("INSERT INTO stories (story_title, story_content, user_id, link_content)
VALUES (?, ?, ?, ?)");
if (!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
}

$stmt->bind_param("ssis", $storyTitle, $storyContent, $userid, $linkContent);
$stmt->execute();
$stmt->close();

echo "<p>You have uploaded the story successfully! Returning to the Home page.</p><br>";
header("refresh: 3; url=showAllStory.php");
?>
