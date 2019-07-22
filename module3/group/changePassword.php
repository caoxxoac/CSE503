<!DOCTYPE html>
<html lang="en">
	<head>
		<title>News - Change Password</title>
	</head>
	
	<body>
		<form name="input" action="changePassword.php" method="POST">
		    <label>Username: </label><input type="text" name="username"/>
			<label>Old Password: </label><input type="password" name="oldpw"/>
			<br>
			<label>New Password: </label><input type="password" name="newpw"/>
			<br>
			
			<button type="submit" name="submit" value="submit">Submit</button>		
		</form>
			<form name="input" action="showAllStory.php" method="POST">
				<button type="submit" name="submit" class="link" value="Return to home">Return to Home</button>
			</form>
</html>

<?php
session_start();
$userid = $_SESSION["userid"];
if ($userid == NULL){
  header("Location: index.html");
}

require "database.php";

	//code borrowed from login page
	if(isset($_POST["submit"])){
		
		$oldpw = $_POST["oldpw"];
		$newpw = $_POST["newpw"];
		$user = $_POST["username"];

		// Use a prepared statement
		$stmt = $mysqli->prepare("SELECT COUNT(*), id, password FROM users WHERE username=?");
		
		//code source: https://stackoverflow.com/questions/27394710/fatal-error-call-to-a-member-function-bind-param-on-boolean)
		if($stmt) {
			// Bind the parameter
			$stmt->bind_param('s', $user);
			//$user = $_POST["username"];
			$stmt->execute();

			// Bind the results
			$stmt->bind_result($cnt, $userid, $pwd_hash);
			$stmt->fetch();
			
			$hashed_password;

			$pwd_guess = $oldpw;
			// Compare the submitted password to the actual password hash

			if($cnt == 1 && password_verify($pwd_guess, $pwd_hash)){
				// Correct old password submitted, (hash and) set new password
				$hashed_password = password_hash($newpw, PASSWORD_DEFAULT);
				
				$stmt->close();
				
				$stmt1 = $mysqli->prepare("UPDATE users SET password=? WHERE username=?");
				if(!$stmt1){
					printf("Query Insert Failed: %s\n", $mysqli->error);
					exit;
				}
				else
				{
					$stmt1->bind_param("ss", $hashed_password, $user);
					$stmt1->execute();
					$stmt1->close();
					
					
					echo "<p> Password changed successfully.</p>";
				}
				
			} else{
				// Incorrect old password submitted.
				echo "<p>Incorrect old password! Try again.</p>";
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