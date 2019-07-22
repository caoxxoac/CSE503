<?php

if(isset($_SESSION["userid"]))
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

$eventTitle = $_POST["eventTitle"];
$eventYear = $_POST["eventYear"];
$eventMonth = $_POST["eventMonth"];
$eventDay = $_POST["eventDay"];
$eventTime = $_POST["eventTime"];

require "database.php";

// Use a prepared statement
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM events WHERE userid=? AND eventYear=? AND eventMonth=? AND eventDay=? AND eventTime=?");

if($stmt) {
	// Bind the parameter
	$stmt->bind_param('issss', $userid, $eventYear, $eventMonth, $eventDay, $eventTime);
	$stmt->execute();

	// Bind the results
	$stmt->bind_result($cnt);
	$stmt->fetch();
  $stmt->close();

	if($cnt >= 1){
    echo json_encode(array(
      "success" => false,
      "message" => "The event already exists!"
    ));
    exit;
	}

  $stmt = $mysqli->prepare("INSERT INTO events (userid, eventTitle, eventYear, eventMonth, eventDay, eventTime) VALUES (?, ?, ?, ?, ?, ?)");
  if ($stmt) {
		$stmt->bind_param("isssss", $userid, $eventTitle, $eventYear, $eventMonth, $eventDay, $eventTime);
    $stmt->execute();

    $stmt->close();
    echo json_encode(array(
      "success" => true,
      "message" => "Create event successfully!"
    ));
  }
  else {
    echo json_encode(array(
      "success" => false,
      "message" => "Insert failed!",
    ));
    exit;
  }
}
else {
  echo json_encode(array(
    "success" => false,
    "message" => "Prepare failed!"
  ));
  exit;
}

?>
