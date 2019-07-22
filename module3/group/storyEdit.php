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
<title>News - Edit Story</title>
</head>
<body style="background: <?php echo $backgroundColor; ?>">

<?php
  require "database.php";

  $stmt = $mysqli->prepare("SELECT story_title, story_content, link_content FROM stories WHERE story_id=?");
  if (!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }
  $stmt->bind_param("i", $storyid);
  $stmt->execute();
  $stmt->bind_result($oldStoryTitle, $oldStoryContent, $oldLinkContent);
  $stmt->fetch();
  $stmt->close();

  if (isset($_POST["storyedit"])){
    $newStoryTitle = $_POST["newStoryTitle"];
    $newStoryContent = $_POST["newStoryContent"];
    $newLinkContent = $_POST["newLinkContent"];
    $oldStoryTitle = $newStoryTitle;
    $oldStoryContent = $newStoryContent;
    $oldLinkContent = $newLinkContent;
    $stmt = $mysqli->prepare("UPDATE stories SET story_title=?, story_content=?, link_content=? WHERE story_id=?");
    if (!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
    }

    $stmt->bind_param("sssi", $newStoryTitle, $newStoryContent, $newLinkContent, $storyid);
    $stmt->execute();
    $stmt->close();


    echo "<p>You have edited the story successfully! Going back to story...</p><br>";
    header("refresh: 4; url=showStory.php");
  }
?>
<form name="create" action="storyEdit.php" method="POST">
  <label>My New Story Title</label><br><input name="newStoryTitle" value="<?php echo $oldStoryTitle;?>"/><br>
  <label>My New Link</label><br><input name="newLinkContent" value="<?php echo $oldLinkContent; ?>"/><br>
  <label>My New Story Content</label><br>
  <textarea name="newStoryContent" rows="5" cols="40"><?php echo $oldStoryContent; ?></textarea><br>
  <input type="hidden" name="storyid" value="<?php echo $storyid; ?>"/>
  <input type="submit" name="storyedit" value="Edit Story"/>
</form>
<form action="showAllStory.php">
  <input type="submit" value="Home"/>
</form>
</body>
</html>
