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

$eventTitle = $_POST["shareTitle"];
$eventYear = $_POST["shareYear"];
$eventMonth = $_POST["shareMonth"];
$eventDay = $_POST["shareDay"];
$eventTime = $_POST["shareTime"];
$shareUser = $_POST["shareUser"];

require "database.php";

$stmt = $mysqli->prepare("SELECT COUNT(*), userid FROM users WHERE username=?");
if (!$stmt){
  echo json_encode(array(
    "success" => false,
    "message" => "Prep failed"
  ));
  exit;
}
$stmt->bind_param("s", $shareUser);
$stmt->execute();
$stmt->bind_result($shareCnt, $shareUserid);
$stmt->fetch();
$stmt->close();
// check if the user you share to exist
if ($shareCnt < 1){
  echo json_encode(array(
    "success" => false,
    "message" => "The user you share to does not exist!"
  ));
  exit;
}

$stmt = $mysqli->prepare("SELECT COUNT(*) FROM events WHERE userid=? AND eventYear=? and eventMonth=? AND eventDay=? AND eventTime=?");
if (!$stmt){
  echo json_encode(array(
    "success" => false,
    "message" => "Prep failed"
  ));
  exit;
}
$stmt->bind_param("issss", $userid, $eventYear, $eventMonth, $eventDay, $eventTime);
$stmt->execute();
$stmt->bind_result($eventCnt);
$stmt->fetch();
$stmt->close();
// check if the event you are sharing exist
if ($eventCnt < 1){
  echo json_encode(array(
    "success" => false,
    "message" => "The event you are sharing does not exist!"
  ));
  exit;
}

// Use a prepared statement
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM events WHERE userid=? AND eventYear=? AND eventMonth=? AND eventDay=? AND eventTime=?");

if($stmt) {
	// Bind the parameter
	$stmt->bind_param('issss', $shareUserid, $eventYear, $eventMonth, $eventDay, $eventTime);
	$stmt->execute();

	// Bind the results
	$stmt->bind_result($cnt);
	$stmt->fetch();
  $stmt->close();

  // make sure the user we share to does not have an event at that time
	if($cnt >= 1){
    echo json_encode(array(
      "success" => false,
      "message" => "The user already have event at that time!"
    ));
    exit;
	}

  $stmt = $mysqli->prepare("INSERT INTO events (userid, eventTitle, eventYear, eventMonth, eventDay, eventTime) VALUES (?, ?, ?, ?, ?, ?)");
  if ($stmt) {
		$stmt->bind_param("isssss", $shareUserid, $eventTitle, $eventYear, $eventMonth, $eventDay, $eventTime);
    $stmt->execute();

    $stmt->close();
    echo json_encode(array(
      "success" => true,
      "message" => "Share event successfully!"
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
