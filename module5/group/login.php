<?php

session_start();

$username = $_POST["username"];
$password = $_POST["password"];


require "database.php";


// Use a prepared statement
$stmt = $mysqli->prepare("SELECT COUNT(*), userid, password FROM users WHERE username=?");

//code source: https://stackoverflow.com/questions/27394710/fatal-error-call-to-a-member-function-bind-param-on-boolean)
if($stmt) {
	// Bind the parameter
	$stmt->bind_param('s', $username);
	$stmt->execute();

	// Bind the results
	$stmt->bind_result($cnt, $user_id, $pwd_hash);
	$stmt->fetch();
  $stmt->close();
	$pwd_guess = $_POST['password'];
	// Compare the submitted password to the actual password hash

	if($cnt == 1 && password_verify($pwd_guess, $pwd_hash)){
		// Login succeeded!
		$_SESSION["userid"] = $user_id;
		$_SESSION["username"] = $username;
		// Redirect to your target page
		// header("Location: calendar.html");
    echo json_encode(array(
      "success" => true,
      "userid" => $user_id,
			"username" => $username,
      "message" => "Logged in!"
    ));
    exit;
	}
	else{
		// Login failed; redirect back to the login screen
		// echo "<p>Login unsuccessful! Try again.</p>";
		// exit;
    echo json_encode(array(
      "success" => false,
      "message" => "Incorrect Username or Password"
    ));
	}
}
else{
	// var_dump($stmt);
	// echo "<p>Error: Cannot connect to database</p>";
  echo json_encode(array(
    "success" => false,
    "message" => "Error: Cannot connect to database"
  ));
	exit;
}
?>
