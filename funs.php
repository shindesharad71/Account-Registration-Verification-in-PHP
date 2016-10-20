<?php
require_once('dbconfig.php');
global $con;

/*******************************
 * function for login into panel.
 *******************************/

function login()
{
	global $con;
	if (isset($_POST['login'])) 
	{
		
		$username = $_POST['username'];
		$username = stripslashes($username);
		$password = $_POST['password'];
		$password = stripslashes($password);

		$query = "SELECT * from intern where username ='$username' AND password ='$password'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		while($row = mysqli_fetch_assoc($result))
		{
			$active = $row['active'];
		}
		if($rows == 1)
		{
			if($active == "0")
			{
				echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Sorry <b>'.$username.'</b>, your account is not activated yet, <b>confirm your email</b> to activate account</span></div>';
			}
			else
			{
				$_SESSION['username'] = $username;
				echo '<div class="text-center alert bg-success col-md-offset-4 col-md-4" role="alert"><span>Welcome back, <b>'.$_SESSION['username'].'</b>!</span></div>';
				if($username == "admin")
				{
					echo '<script>setTimeout(function () { window.location.href = "admin.php";}, 1000);</script>';
				}
				else
				{
					echo '<script>setTimeout(function () { window.location.href = "home.php";}, 1000);</script>';
				}
				
			}
			
		}
		else
		{
			echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Sorry <b>'.$username.'</b>, Try Again!</span></div>';
		}	
	}

	return false;
}


/*******************************
 * to check for authorized user.
 *******************************/

function check_session()
{
	if( !isset($_SESSION["username"]) )
	{
    	header("location:index.php");
    	exit();
	}	
    return false;
}

/*******************************
 * Register new user.
 *******************************/

function register()
{
	global $con;

	if(isset($_POST['register'])) 
	{
		
		$email = $_POST['email'];
		$email = stripslashes($email);
		$username = $_POST['username'];
		$username = stripslashes($username);
		$password = $_POST['password'];
		$password = stripslashes($password);
		$code = md5($email);
		$active = "0";
		$status = "0";
		$query = "INSERT into intern (email, username, password, active, code, status) VALUES ('$email', '$username', '$password', '$active', '$code', '$status')";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);

		if($rows == 1)
		{
			$message = "Hello User, \n\r Click on this link to activate your account \r\n
			http://sharadshinde.in/intern/activate.php?code=".$code;
			$header="from: Account Activation <admin@sharadshinde.in>";
			$sendit = mail($email, "Account Activation", $message);

			if($sendit)
			{
				echo '<div class="text-center alert bg-success col-md-offset-4 col-md-4">Success! User Registered Successfully!, Please <b>Confirm Your Email to Activate Account</b></div>';
			}
			else
			{
				echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4">error while sending mail, try again</div>';
			}
			
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4">error while registering user, try again</div>';
		}
	}

	return false;
}

/*******************************
 * calculate count of all members.
 *******************************/

function get_all_status()
{
	global $con;
	$query = "SELECT * FROM intern";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);
	return $rows;
}

/*******************************
 * calculate count of unactivated members
 *******************************/

function get_unverified_status()
{
	global $con;
	$query = "SELECT * FROM intern where active LIKE '0'";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);
	return $rows;
}

/*******************************
 * retrive all member data in table format.
 *******************************/

function all_users($filter)
{
	global $con;
	if($filter = "name")
	{
		$query = "SELECT * FROM intern ORDER BY username ASC";
	}
	elseif($filter = "date")
	{
		$query = "SELECT * FROM intern ORDER BY id DESC";
	}
	else
	{
		$query = "SELECT * FROM intern ORDER BY id DESC";
	}
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);
	?>
	<table class="table table-bordered table-responsive">
			<tr class="alert-info">
				<th><h4>Username</h4></th>
				<th><h4>Email</h4></th>
				<th><h4>Email Status</h4></th>
				<th><h4>Action</h4></th>
			</tr>
	<?php
	while($row = mysqli_fetch_assoc($result))
		{

			if($row['status'] == "0")
				$email_status = "Unverified";
			if($row['status'] == "1")
				$email_status = "Verified";

			echo '<tr>
				<td>'.$row['username'].'</td>
				<td>'.$row['email'].'</td>
				<td>'.$email_status.'</td>';
				if($row['username'] == 'admin')
				{
					echo '<td>-</td>';
				}
				elseif ($row['active'] == "1") 
				{
					echo '<td><a class="btn btn-danger" href="admin_delete.php?id='.$row['id'].'">Delete</a></td>';
				}
				else
				{
					echo '<td><a class="btn btn-success" href="admin_activate.php?id='.$row['id'].'">Activate</a>';
					echo ' | <a class="btn btn-danger" href="admin_delete.php?id='.$row['id'].'">Delete</a></td>';
				}
			echo '</tr>';
		}
	echo '</table>';
	return false;
}

/*******************************
 * delete user record.
 *******************************/

function delete_user($id)
{
	global $con;
	$id = $id;

	if(isset($_POST['yes']))
	{
		$query = "DELETE from intern where id='$id'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert bg-success col-md-offset-4 col-md-4" role="alert"><span>Success! Member removed</div>';
			echo '<script>setTimeout(function () { window.location.href = "admin.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Error while deleting member</div>';
		}
	}
	
	return false;
}

/*******************************
 * Activate user record.
 *******************************/

function activate_user($id)
{
	global $con;
	$id = $id;

	if(isset($_POST['yes']))
	{
		$check = "SELECT status from intern where id='$id'";
		$result = mysqli_query($con,$check);
		while($row = mysqli_fetch_assoc($result))
		{
			$is_mail_varified = $row['status'];
		}

		if($is_mail_varified == "1")
		{
			$query = "UPDATE intern SET active='1' WHERE id='$id'";
			$result = mysqli_query($con,$query);
			$rows = mysqli_affected_rows($con);
			if($rows == 1)
			{
				echo '<div class="text-center alert bg-success col-md-offset-4 col-md-4" role="alert"><span>Success! Member Activated</div>';
				echo '<script>setTimeout(function () { window.location.href = "admin.php";}, 1000);</script>';
			}
			else
			{
				echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Error while activating member</div>';
				echo '<script>setTimeout(function () { window.location.href = "admin.php";}, 1000);</script>';
			}
		}
		else
		{
			echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Sorry, <b>user not Verified Email yet</b></div>';
			echo '<script>setTimeout(function () { window.location.href = "admin.php";}, 1000);</script>';
		}
		
	}
	
	return false;
}