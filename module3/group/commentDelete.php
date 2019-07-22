<?php
session_start();
$commentID = $_POST["commentid"];
$userid = $_SESSION["userid"];
if ($userid == NULL){
  header("Location: index.html");
  exit;
}

require "database.php";
$stmt = $mysqli->prepare("SELECT user_id FROM comments WHERE comment_id=?");
if (!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
}
$stmt->bind_param("i", $commentID);
$stmt->execute();
$stmt->bind_result($commentUserid);
$stmt->close();

if ($commentUserid == $userid){
  $stmt = $mysqli->prepare("DELETE FROM comments WHERE comment_id=?");
  if (!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }
  $stmt->bind_param("i", $commentID);
  $stmt->execute();
  $stmt->close();

  echo "<p>The comment has been deleted! Going back to story...</p>";
  header("refresh: 3; url=showAllStory.php");
}
else {
  echo "<p>You cannot delete comments from other people! Going back to story...</p>";
  header("refresh: 3; url=showAllStory.php");
}
?>
