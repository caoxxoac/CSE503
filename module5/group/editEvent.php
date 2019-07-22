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

$originalTitle = $_POST["originalTitle"];
$originalYear = $_POST["originalYear"];
$originalMonth = $_POST["originalMonth"];
$originalDay = $_POST["originalDay"];
$originalTime = $_POST["originalTime"];

$updateTitle = $_POST["updateTitle"];
$updateYear = $_POST["updateYear"];
$updateMonth = $_POST["updateMonth"];
$updateDay = $_POST["updateDay"];
$updateTime = $_POST["updateTime"];

require "database.php";

// Use a prepared statement
$stmt = $mysqli->prepare("SELECT COUNT(*), eventID FROM events WHERE eventTitle=? AND userid=? AND eventYear=? AND eventMonth=? AND eventDay=? AND eventTime=?");

if($stmt) {
	// Bind the parameter
	$stmt->bind_param('sissss', $originalTitle, $userid, $originalYear, $originalMonth, $originalDay, $originalTime);
	$stmt->execute();

	// Bind the results
	$stmt->bind_result($cnt, $eventID);
	$stmt->fetch();
  $stmt->close();

	if($cnt < 1){
    echo json_encode(array(
      "success" => false,
      "message" => "The event you are editing does not exist!"
    ));
    exit;
	}

  $stmt = $mysqli->prepare("UPDATE events SET eventTitle=?, eventYear=?, eventMonth=?, eventDay=?, eventTime=? WHERE eventID=?");
  if ($stmt) {
		$stmt->bind_param("sssssi", $updateTitle, $updateYear, $updateMonth, $updateDay, $updateTime, $eventID);
    $stmt->execute();
    $stmt->close();
    echo json_encode(array(
      "success" => true,
      "message" => "Update successfully!"
    ));
  }
  else {
    echo json_encode(array(
      "success" => false,
      "message" => "Update failed!"
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
