<?php
	require_once "pdo.php";                                                      //Add

	// if (  isset($_SESSION['user_id']) )
	// {
	// 	die('Not logged in');
	// }
	function test_input($data)
	{
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
	if(isset($_POST['add']))
	{

		if(!isset($_POST['name']))
		{
			echo("Name is missing!!!");
		}
		else if(!isset($_POST['email']))
		{
			echo("Email is missing!!!");
		}
		else if(!isset($_POST['password']))
		{
			echo("Password is missing!!!");
		}
		else if($_POST['confirmpassword']!=$_POST['password'])
		{
			echo("Passwords do not match!!!");
		}
		else if( isset($_POST['name']) && isset($_POST['email']))
		{

				$_SESSION["email"]=$_POST["email"];
				$email = test_input($_SESSION["email"]);
				unset($_SESSION["email"]);
				if (!filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					$_SESSION['error'] = "Email must have an at-sign (@)";
					if ( isset($_SESSION['error']) )
					{
						echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
						unset($_SESSION['error']);
					}
					return;
				}
				else
				{
					echo("<p>Handling POST data...</p>\n");
					$sql = "INSERT INTO users (name,email,password,userRole) VALUES (:name, :em,:password,:userRole)";
					$stmt = $pdo->prepare($sql);

					$stmt->execute(array(':name' => $_POST['name'],':em' => $_POST['email'],':password' => $_POST['password'],':userRole' => 2));
					session_start();
					$_SESSION['flash'] = "Record inserted";
						$_SESSION['user_id'] = $pdo->lastInsertId();
						$_SESSION['name'] = $_POST['name'];
						$_SESSION['userRole'] = 2;
						header("Location: usersView.php");
						return;
				}
		}
	}



	/*if ( isset($_POST['delete']) )
	{
		$sql = "DELETE FROM autos WHERE auto_id = :zip";
		//echo "<pre>\n$sql\n</pre>\n";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(':zip' => $_POST['auto_id']));
	}
*/
?>
<!DOCTYPE html>
<html>
<head>
<title>Cars List</title>
</head>
<body>
<p>Add A New Profile</p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<p>Name:
<input type="text" name="name"></p>
<p>Email:
<input type="text" name="email" ></p>
<p>Password:
<input type="password" name="password" ></p>
<p>Confirm Password:
<input type="password" name="confirmpassword" ></p>
<p>
<input type="submit" name="add" value="Add"></p>
</form>

</body>
</html>
