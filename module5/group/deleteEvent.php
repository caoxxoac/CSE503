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

$eventTitle = $_POST["deleteTitle"];
$eventYear = $_POST["deleteYear"];
$eventMonth = $_POST["deleteMonth"];
$eventDay = $_POST["deleteDay"];
$eventTime = $_POST["deleteTime"];

require "database.php";

// Use a prepared statement
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM events WHERE eventTitle=? AND userid=? AND eventYear=? AND eventMonth=? AND eventDay=? AND eventTime=?");

if($stmt) {
	// Bind the parameter
	$stmt->bind_param('sissss', $eventTitle, $userid, $eventYear, $eventMonth, $eventDay, $eventTime);
	$stmt->execute();

	// Bind the results
	$stmt->bind_result($cnt);
	$stmt->fetch();
  $stmt->close();

	if($cnt < 1){
    echo json_encode(array(
      "success" => false,
      "message" => "Current user does not have such event!"
    ));
    exit;

	}

  $stmt = $mysqli->prepare("DELETE FROM events WHERE eventTitle=? AND userid=? AND eventYear=? AND eventMonth=? AND eventDay=? AND eventTime=?");
  if ($stmt) {
    $stmt->bind_param('sissss', $eventTitle, $userid, $eventYear, $eventMonth, $eventDay, $eventTime);
    $stmt->execute();

    $stmt->close();
    echo json_encode(array(
      "success" => true,
      "message" => "Delete successfully!"
    ));
  }
  else {
    echo json_encode(array(
      "success" => false,
      "message" => "Delete failed!"
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
