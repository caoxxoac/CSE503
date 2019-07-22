<?php
session_start();
$storyid = $_POST["storyid"];
$userid = $_SESSION["userid"];
if ($userid == NULL){
  header("Location: index.html");
  exit;
}

require "database.php";
$stmt = $mysqli->prepare("DELETE FROM stories WHERE story_id=?");
if (!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
}
$stmt->bind_param("i", $storyid);
$stmt->execute();
$stmt->close();

// since the story is deleted, the comment should exist no more
$stmt = $mysqli->prepare("DELETE FROM comments WHERE story_id=?");
if (!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
}
$stmt->bind_param("i", $storyid);
$stmt->execute();
$stmt->close();


echo "<p>The story has been deleted! Going back to Home page...</p>";
header("refresh: 4; url=showStory.php");
?>
