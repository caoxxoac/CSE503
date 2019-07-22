<!doctype html>
<html lang="en">
<head>
  <title>News - Login</title>
  <link rel="stylesheet" type="text/css" href="loginStyle.css"/>
</head>

<body>
  <h1>Login to Your Account</h1>
  <form name="input" action="login.php" method="POST">
    <label>Username:</label> <br/>
	<input type="text" name="username"><br/>

	<br/>

	<label>Password:</label> <br/>
    <input type="password" name="password"/>

    <br>
    <button type = "submit" name = "login" value = "login">Login</button>

	<br/>
	<br/>

    <p>If you don't have an account, <a href="register.html">register here</a>.</p>
	<br>
	<a href="showAllStory.php">Login as a Guest</a>
  </form>


  <?php

	if(isset($_SESSION['user_id']))
	{
		header("Location: story.php");
		exit;
	}

	session_start();

	if (isset($_POST['value']))
	{
	  $username = $_POST["username"];
	  $password = $_POST["password"];
	}


	require "database.php";

	if(isset($_POST["login"])){

		// Use a prepared statement
		$stmt = $mysqli->prepare("SELECT COUNT(*), id, password FROM users WHERE username=?");

		//code source: https://stackoverflow.com/questions/27394710/fatal-error-call-to-a-member-function-bind-param-on-boolean)
		if($stmt) {
			// Bind the parameter
			$stmt->bind_param('s', $user);
			$user = $_POST['username'];
			$stmt->execute();

			// Bind the results
			$stmt->bind_result($cnt, $user_id, $pwd_hash);
			$stmt->fetch();

			$pwd_guess = $_POST['password'];
			// Compare the submitted password to the actual password hash

			if($cnt == 1 && password_verify($pwd_guess, $pwd_hash)){
				// Login succeeded!
				$_SESSION['userid'] = $user_id;
				// Redirect to your target page
				header("Location: showAllStory.php");
			} else{
				// Login failed; redirect back to the login screen
				echo "<p>Login unsuccessful! Try again.</p>";
				exit;
			}
		}
		else{
			var_dump($stmt);
			echo "<p>Error: Cannot connect to database</p>";
			exit;
		}
		//end source (only included if/else)

	}
  ?>

</body>
</html>
