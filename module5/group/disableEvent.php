<?php

if(isset($_SESSION['userid'])){
	echo json_encode(array(
    "success" => false,
    "message" => "login first before you add event!"
  ));
	exit;
}

require "database.php";

session_start();
$userid = $_SESSION["userid"];
$username = $_SESSION["username"];

$eventYear = $_POST["eventYear"];
$eventMonth = $_POST["eventMonth"];

$monthEvents = array();

$stmt = $mysqli->prepare("SELECT eventTitle, eventDay, eventTime FROM events WHERE eventYear=? AND eventMonth=? AND userid=?");
if (!$stmt){
  echo json_encode(array(
    "success" => false,
    "message" => "Prep failed"
  ));
  exit;
}
$stmt->bind_param("ssi", $eventYear, $eventMonth, $userid);
$stmt->execute();
$stmt->bind_result($eventTitle, $eventDay, $eventTime);

while ($stmt->fetch()){
  $currentEvent = array(
    "eventTitle" => $eventTitle,
    "eventDay" => $eventDay,
    "eventTime" => $eventTime,
    "username" => $username,
    "success" => true,
    "message" => "All events are hidden now",
    "username" => $username
  );
  array_push($monthEvents, $currentEvent);
}
echo json_encode($monthEvents);

$stmt->close();
?>
