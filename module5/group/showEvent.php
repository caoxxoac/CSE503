<?php

if(isset($_SESSION['userid']))
{
	echo json_encode(array(
    "success" => false,
    "message" => "login first before you add event!"
  ));
	exit;
}

session_start();
$userid = $_SESSION["userid"];
$username = $_SESSION["username"];

require "database.php";

$eventYear = $_POST["eventYear"];
$eventMonth = $_POST["eventMonth"];

$monthEvents = array();

// Use a prepared statement
$stmt = $mysqli->prepare("SELECT eventTitle, eventDay, eventTime FROM events WHERE userid=? AND eventYear=? AND eventMonth=?");

if($stmt) {
	// Bind the parameter
	$stmt->bind_param('iss', $userid, $eventYear, $eventMonth);
	$stmt->execute();

	// Bind the results
	$stmt->bind_result($eventTitle, $eventDay, $eventTime);

  while ($stmt->fetch()){
    $currentEvent = array(
      "eventTitle" => $eventTitle,
      "eventYear" => $eventYear,
      "eventMonth" => $eventMonth,
      "eventDay" => $eventDay,
      "eventTime" => $eventTime
    );
    array_push($monthEvents, $currentEvent);
  }
  echo json_encode($monthEvents);

	$stmt->close();
}
else {
  echo json_encode(array(
    "success" => false,
    "message" => "Prepare failed!"
  ));
  exit;
}

?>
