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

$password1 = $_POST["password1"];
$password2 = $_POST["password2"];

require "database.php";

if ($password1 == "" || $password2 == ""){
  echo json_encode(array(
    "success" => false,
    "message" => "Please fill in both fields!"
  ));
  exit;
}

if ($password1 != $password2) {
  echo json_encode(array(
    "success" => false,
    "message" => "new passwords should be consistent!!!"
  ));
  exit;
}


$hashed_password = password_hash($password1, PASSWORD_DEFAULT);

$stmt = $mysqli->prepare("UPDATE users SET password=? WHERE userid=?");

if($stmt) {
	// Bind the parameter
	$stmt->bind_param('si', $hashed_password, $userid);
	$stmt->execute();

  $stmt->close();
  echo json_encode(array(
    "success" => true,
    "message" => "Congratulations! You have changed the password successfully!"
  ));
}
else {
  echo json_encode(array(
    "success" => false,
    "message" => "Prepare failed!"
  ));
  exit;
}

?>
