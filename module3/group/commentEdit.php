<!DOCTYPE html>
<?php
session_start();
$commentID = $_POST["commentid"];
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
<title>My Comment Editing Page</title>
</head>
<body style="background: <?php echo $backgroundColor; ?>">


<?php
  require "database.php";
  $stmt = $mysqli->prepare("SELECT comment_content, user_id FROM comments WHERE comment_id=?");
  if (!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }
  $stmt->bind_param("i", $commentID);
  $stmt->execute();
  $stmt->bind_result($oldCommentContent, $commentUserid);
  $stmt->fetch();
  $stmt->close();

  if ($commentUserid == $userid){
    echo "<form name='create' action='commentEdit.php' method='POST'>
      <label>Comment Area: </label><br>
      <textarea name='commentcontent' rows='5' cols='40'>
      </textarea><br>
      <input type='hidden' name='commentid' value='$commentID'/>
      <input type='submit' name='addcomment' value='Comment'/>
    </form>";
    echo "<form action='story.php'>
      <input type='submit' value='Home'/>
    </form>";
    if (isset($_POST["addcomment"])){
      $newCommentContent = $_POST["commentcontent"];
      $oldCommentContent = $newCommentContent;
      $stmt = $mysqli->prepare("UPDATE comments SET comment_content=? WHERE comment_id=?");
      if (!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }

      $stmt->bind_param("si", $newCommentContent, $commentID);
      $stmt->execute();
      $stmt->close();


      echo "<p>You have edited the comment successfully! Going back to story...</p><br>";
      header("refresh: 3; url=showAllStory.php");
    }
  }
  else{
    echo "<p>You cannot edit comments from someone else! Going back to story...</p>";
    header("refresh: 3; url=showAllStory.php");
  }
?>
</body>
</html>
