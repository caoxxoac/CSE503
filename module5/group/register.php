<?php
session_start();

require "database.php";
$username = $_POST["username"];
$password = $_POST["password"];
// $firstname = $_POST["firstname"];
// $lastname = $_POST["lastname"];
// $email = $_POST["email"];

if (empty($username)){
  echo json_encode(array(
    "success" => false,
    "message" => "Username cannot be empty"
  ));
  exit;
}
if (empty($password)){
  echo json_encode(array(
    "success" => false,
    "message" => "The password cannot be empty"
  ));
  exit;
}
// if (empty($firstname)){
  // echo "<p>The first name cannot be empty</p>";
  // exit;
// }
// if (empty($lastname)){
  // echo "<p>The last name cannot be empty</p>";
  // exit;
// }

// if (!ereg("^.+@.+\.(com|org|edu|net|gov)$", $email)){
//   echo "<p>Email address should look like this form</p><i>username@hostname.
//   (com|org|edu|net|gov)</i>";
//   exit;
// }

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username=?");
if (!$stmt){
  // printf("Query Prep Failed: %s\n", $mysqli->error);
  echo json_encode(array(
    "success" => false,
    "message" => "Query Prep Failed"
  ));
  exit;
}

// bind parameters
$stmt->bind_param("s", $username);
$stmt->execute();

$stmt->bind_result($userCount);
$stmt->fetch();

// if the count is 1, then the user exists
if ($userCount == 1){
  //echo "<p>The username already exists. Try other username again</p>";
  echo json_encode(array(
    "success" => false,
    "message" => "The username already exists. Try a different username."
  ));
  $stmt->close();
  exit;
}
$stmt->close();

//$query = "INSERT INTO users (username, password, first_name, last_name, email) VALUES ($username, $password, $firstname, $lastname, $email)";
//$stmt = $mysqli->query($query);

$stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
if(!$stmt){
	// printf("Query Insert Failed: %s\n", $mysqli->error);
  echo json_encode(array(
    "success" => false,
    "message" => "Query Insert Failed"
  ));
  $stmt->close();
	exit;
}
$stmt->bind_param("ss", $username, $hashed_password);
$stmt->execute();
$stmt->close();

// echo "<p>Congratulations! You have been registered successfully! Returning to Login page.</p>";
// header("refresh: 3; url=index.html");
echo json_encode(array(
  "success" => true,
  "message" => "Congratulations! You have been registered successfully!"
));
?>
