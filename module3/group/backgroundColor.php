<?php
session_start();
$allColor = array("#F0F8FF", "#FAEBD7", "#00FFFF", "#7FFFD4",
"#0000FF", "#00FFFF", "#7CFC00", "#FF0000", "#FFF5EE", "#4682B4");
$currentColor = $allColor[array_rand($allColor, 1)];
$_SESSION["bgcolor"] = $currentColor;
header("Location: showAllStory.php");
?>
