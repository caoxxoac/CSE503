<?php
session_start();
$storyid = $_POST["storyid"];
$storyTitle = $_POST["storytitle"];
$storyContent = $_POST["storycontent"];

$filename = "/home/tcornell/file/story$storyid.txt";
$handle = fopen($filename, "w") or die("Open the file unsuccessfully!!!");
fwrite($handle, $storyTitle);
fwrite($handle, "\n");
fwrite($handle, $storyContent);
fclose($handle);
header("Content-Type: text/plain");
header("Content-Disposition: attachment; filename='story$storyid.txt'");
readfile($filename);
unlink($filename);
?>
