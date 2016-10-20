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
		$query = "INSERT into intern (email, username, password, active, code) VALUES ('$email', '$username', '$password', '$active', '$code')";
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
 * calculate count of unverified members
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

function all_users()
{
	global $con;
	$query = "SELECT * FROM intern";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);
	?>
	<table class="table table-bordered table-responsive">
			<tr class="alert-info">
				<th><h4>Username</h4></th>
				<th><h4>Email</h4></th>
				<th><h4>Status</h4></th>
			</tr>
	<?php
	while ($row = mysqli_fetch_assoc($result))
		{
			
			if($row['active'] == "0")
				$status = "Unverified";
			if($row['active'] == "1")
				$status = "Verified";

			echo '<tr>
				<td>'.$row['username'].'</td>
				<td>'.$row['email'].'</td>
				<td>'.$status.'</td>';
			echo '</td></tr>';
		}
	echo '</table>';
	return false;
}

/*******************************
 * Add new member.
 *******************************/

function add_member($role)
{
	global $con;
	$role = $role;

	if (isset($_POST['add_member'])) 
	{
		$name = $_POST['name'];
		$name = stripslashes($name);
		$email = $_POST['email'];
		$email = stripslashes($email);
		$username = $_POST['username'];
		$username = stripslashes($username);
		$password = $_POST['password'];
		$password = stripslashes($password);
		$pic = 'imgs/user.png';

		if($role == 'President')
		{
			$select_role = $_POST["role"];

		}
		else
		{
			$select_role = "-";
		}

		$query = "INSERT into userinfo (name,  email, username, password, role, pic) VALUES ('$name',  '$email', '$username', '$password', '$select_role', '$pic')";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Success! Member Added</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "manage_members.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>error while adding member, try again</b></p></div>';
		}
	}

	return false;
}

/*******************************
 * edit member infrmation.
 *******************************/

function edit_member($role,$mem_id)
{
	global $con;
	$role = $role;
	$mem_id = $mem_id;

	if (isset($_POST['edit_member']))
	{
		$edit_name = $_POST['name'];
		$edit_name = stripslashes($edit_name);
		$edit_email = $_POST['email'];
		$edit_email = stripslashes($edit_email);
		$edit_username = $_POST['username'];
		$edit_username = stripslashes($edit_username);
		
		if($role = 'President')
		{
			$edit_select_role = $_POST['role'];
		}
		else
		{
			$edit_select_role = "";
		}

		if(empty($edit_select_role))
		{
			$query = "UPDATE userinfo SET name='$edit_name', email='$edit_email', username='$edit_username' WHERE id='$mem_id'";
		}
		else
		{
			$query = "UPDATE userinfo SET name='$edit_name', email='$edit_email', username='$edit_username', role='$edit_select_role' WHERE id='$mem_id'";
		}
		
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Success! info updated</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "manage_members.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>error while updating info, try again</b></p></div>';
		}
	}

	return false;
}

/*******************************
 * delete member record.
 *******************************/

function delete_member($mem_id,$role)
{
	global $con;
	$mem_id = $mem_id;
	$role = $role;

	if(isset($_POST['yes']))
	{
		$query = "DELETE from userinfo where id='$mem_id'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		echo mysqli_error($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Success! Member removed</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "manage_members.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>error while removing member, try again</b></p></div>';
		}
	}
	
	return false;
}

/*******************************
 * forgot password function.
 *******************************/

function forgot()
{
	global $con;
	$otp = mt_rand(111111, 999999);
	if(isset($_POST['send_code']))
	{
		$email = $_POST['email'];
		$query = "SELECT * from userinfo where email='$email'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			$query = "UPDATE userinfo SET otp='$otp' where email='$email'";
			$result = mysqli_query($con,$query);
			$rows = mysqli_affected_rows($con);
			if($rows == 1)
			{
				// Pear Mail Library
				require_once "Mail.php";
				$from = '<shindesharad71@gmail.com>';
				$subject = 'Club - Password Reset Code';
				$body = "Code is: ".$otp;
				$headers = array(
				    'From' => $from,
				    'To' => $email,
				    'Subject' => $subject
				);
				$smtp = Mail::factory('smtp', array(
				        'host' => 'ssl://smtp.gmail.com',
				        'port' => '465',
				        'auth' => true,
				        'username' => 'shindesharad71@gmail.com',
				        'password' => 'password'
				    ));
				$mail = $smtp->send($to, $headers, $body);
				if (PEAR::isError($mail)) 
				{
				    echo('<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p>' . $mail->getMessage() . '</p></div>');
				} 
				else 
				{
				    echo('<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Password reset code sent to '.$email.' check your mailbox</b></p></div>');
				}
			}
			else
			{
				echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>error in generating opt</b></p></div>';
			}
		
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>invalid email! try again</b></p></div>';
		}
	
	}
	
	return false;
}

/*******************************
 * show all session and events.
 *******************************/

function show_events($role)
{
	global $con;
	$query = "SELECT * FROM sessions ORDER by session_date ASC";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);

	if($rows == 0)
	{
		echo '<div class="text-center alert alert-info col-md-offset-4 col-md-4"><p><b>no events scheduled yet!</b></p></div>';
	}
	
	while($row = mysqli_fetch_assoc($result))
	{
		if(time() >= strtotime($row['session_date']))
		{
			$choose_css = "panel-red";
		}
		else
		{
			$choose_css = "panel-teal";
		}
		?>
			
		<div class="col-md-4">
			<div class="panel <?php echo $choose_css; ?>">
				<div class="panel-heading dark-overlay"><?php echo $row['session_name']; ?></div>
					<div class="panel-body">
						<p>
						<b>Date:</b> <small><?php echo date('jS M Y H:i', strtotime($row['session_date'])); ?></small><br>
						<?php echo $row['session_details']; ?>
						</p>
					</div>
					<?php
						if($role == 'President')
		        		{
		        			echo '<div class="panel-footer"><a class="btn btn-primary btn-sm" href="edit_event.php?event_id='.$row['session_id'].'">Edit</a> <a class="btn btn-danger btn-sm pull-right" href="delete_event.php?event_id='.$row['session_id'].'">Delete</a></div>';
		        		}
					?>
			</div>
		</div>
	<?php
	}

	return false;
}

/*******************************
 * events in table format.
 *******************************/

function all_events_table($role)
{
	$role = $role;

	if($role == "President" || $role == "Technical")
	{
		global $con;
		$query = "SELECT * FROM sessions";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 0)
		{
			echo '<div class="col-md-offset-3 col-md-5 alert alert-warning text-center"><b>no event scheduled, schedule event first!</b></div>';
			exit();
		}
		?>
		<table class="table manage-member-panel table-hover table-responsive">
				<tr class="alert-info">
					<th><h4>Id</h4></th>
					<th><h4>Event Title</h4></th>
					<th><h4>Description</h4></th>
					<th><h4>Date</h4></th>
					<th><h4>Action</h4></th>
				</tr>
		<?php
		while ($row = mysqli_fetch_assoc($result))
			{
				echo '<tr>
					<td>'.$row['session_id'].'</td>
					<td>'.$row['session_name'].'</td>
					<td>'.$row['session_details'].'</td>
					<td>'.$row['session_date'].'</td>
					<td><a href="edit_event.php?event_id='.$row['session_id'].'">Edit</a>';
					echo ' | <a href="delete_event.php?event_id='.$row['session_id'].'">Remove</a></td></tr>';
			}
		echo '</table>';
		}
	return false;
}

/*******************************
 * add new event.
 *******************************/

function add_event()
{
	global $con;
	if (isset($_POST['add_event'])) 
	{
		$name = $_POST['name'];
		$name = stripslashes($name);
		$description = $_POST['description'];
		$description = stripslashes($description);
		$date = $_POST['date'];

		$query = "INSERT into sessions (session_name,  session_details, session_date) VALUES ('$name',  '$description', '$date')";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Success! event Added</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "schedule.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>error while adding event, try again</b></p></div>';
		}
	}

	return false;
}

/*******************************
 * delete event.
 *******************************/

function delete_event($event_id,$role)
{
	global $con;
	$event_id = $event_id;
	$role = $role;

	if(isset($_POST['yes']))
	{
		$query = "DELETE from sessions where session_id='$event_id'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		echo mysqli_error($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Success! Event removed</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "schedule.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>error while removing session, try again</b></p></div>';
		}
	}
	
	return false;
}

/*******************************
 * edit event information.
 *******************************/

function edit_event($event_id,$role)
{
	global $con;
	$role = $role;
	$event_id = $event_id;

	if (isset($_POST['edit_event']))
	{
		$name = $_POST['name'];
		$name = stripslashes($name);
		$description = $_POST['description'];
		$description = stripslashes($description);
		$date = $_POST['date'];
		
		$query = "UPDATE sessions SET session_name='$name', session_details='$description', session_date='$date' WHERE session_id='$event_id'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Success! info updated</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "schedule.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>error while updating info, try again</b></p></div>';
		}
	}

	return false;
}

/*******************************
 * show present and absent members attendance
 *******************************/

function attendance($session_id,$role)
{
	global $con;
	$session_id = $session_id;

	$query = "SELECT * from attendance where session_id='$session_id'";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);

	$key = str_rot13($session_id);

	if($rows == 1)
	{
		$query = "SELECT * from attendance where session_id='$session_id'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);

		?>
		<div class="row">
			<div class="col-md-5">
				<table class="table table-responsive">
				<tr class="success"><th>ID</th><th>Present Members Name</th></tr>
		<?php

		// Present Code from here
		while ($row = mysqli_fetch_assoc($result))
		{
			$string_ids = unserialize($row['id_array']);
			foreach($string_ids as $key => $value)
			{
			    $query = "SELECT * FROM userinfo where id='$value'";
				$result = mysqli_query($con,$query);
				$rows = mysqli_affected_rows($con);
				if($rows == 0)
				{
					echo '<tr class="success"><td>no one is present, error!</td>';
				}
				while ($row = mysqli_fetch_assoc($result))
				{
					echo '<tr class="success"><td>'.$row['id'].'</td>
					<td>'.$row['name'].'</td></tr>';
				}
			}			
			?>
				</table>
				</div>
				<div class="col-md-5">
					<table class="table table-responsive">
						<tr class="danger"><th>ID</th><th>Absent Members Name</th></tr>
					
						<?php
						// Absent Code from here

						$query = "SELECT id FROM userinfo";
						$result = mysqli_query($con,$query);
						$rows = mysqli_affected_rows($con);
						$all_id_array = array();
						while ($row = mysqli_fetch_assoc($result))
						{
							array_push($all_id_array, $row['id']);
						}

						$absent_array = array('0' => '');
						$absent_array = array_diff($all_id_array,$string_ids);
						foreach($absent_array as $key => $value)
						{
						  	$query = "SELECT * FROM userinfo where id='$value'";
							$result = mysqli_query($con,$query);
							$rows = mysqli_affected_rows($con);

							if($rows == 0)
							{
								echo '<tr class="danger"><td>everyone is present, nice guys!</td>';
							}

							while ($row = mysqli_fetch_assoc($result))
							{
								echo '<tr class="danger"><td>'.$row['id'].'</td>
								<td>'.$row['name'].'</td></tr>';
							}
						}
						?>
					</table>
				</div>
			</div>
		<?php
		}
	}
	else
	{
		if($role == "President" || $role == "Technical")
		{
			echo '<br><div class="text-center"><a href="manage_attendance.php?key='.$key.'" class="btn btn-primary">Fill Attendance for this Session</a></div>';
		}
		else
		{
			echo '<div class="text-center alert alert-info col-md-offset-4 col-md-4"><p><b>Attendance is not updated for this session, Please contanct your Technical Head or President for attendance!</b></p></div>';
		}
		
	}

	return false;
}

/*******************************
 * submit attendace in database.
 *******************************/

function do_attendance($key)
{
	global $con;

	if(isset($_POST['submit_attendance']))
	{
		
		$query = "SELECT session_id FROM attendance WHERE session_id='$key'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-warning col-md-offset-4 col-md-4"><p><b>Attendance Already added!</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "attendance.php";}, 1000);</script>';
			exit();
		}
		
		$string_ids = serialize($_POST['checkbx']);

		$query = "INSERT into attendance (session_id, id_array) VALUES ('$key', '$string_ids')";
		$result = mysqli_query($con,$query);
		echo mysqli_error($con);
		$rows = mysqli_affected_rows($con);

		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Success! Attendance updated!</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "attendance.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>error while updating attendance, try again</b></p></div>';
		}

	}
	return false;
}

/*******************************
 * Display Notice board.
 *******************************/

function show_notice($role)
{
	global $con;
	$query = "SELECT * FROM notice ORDER by date DESC";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);

	if($rows == 0)
	{
		echo '<div class="text-center alert alert-info col-md-offset-4 col-md-4"><p><b>no notice posted yet!</b></p></div>';
		exit();
	}
	
	$select = 1;
	while($row = mysqli_fetch_assoc($result))
	{
		if($select%2 == 1)
		{
			$css = 'panel-teal';
		}
		else
		{
			$css = 'panel-orange';
		}
		?>

		<div class="col-md-4">
			<div class="panel <?php echo $css; ?>">
			<div class="panel-heading dark-overlay"><?php echo $row['title']; ?></div>
				<div class="panel-body">
					<p>
					<b>Date:</b> <small><?php echo date('jS M Y H:i', strtotime($row['date'])); ?></small><br>
					<?php echo $row['description']; ?>
					</p>
				</div>
				<?php
					if($role == 'President')
	        		{
	        			echo '<div class="panel-footer"><a class="btn btn-primary btn-sm" href="edit_notice.php?notice_id='.$row['notice_id'].'">Edit</a> <a class="btn btn-danger btn-sm pull-right" href="delete_notice.php?notice_id='.$row['notice_id'].'">Delete</a></div>';
	        		}
				?>
			</div>
		</div>
		<?php
		$select++;
	}

	return false;
}

/*******************************
 * Add new Notice.
 *******************************/

function add_notice()
{
	global $con;
	if (isset($_POST['add_notice'])) 
	{
		$name = $_POST['name'];
		$name = stripslashes($name);
		$description = $_POST['description'];
		$description = stripslashes($description);
		$date = $_POST['date'];

		$query = "INSERT into notice (title,  description, date) VALUES ('$name',  '$description', '$date')";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success bg-success col-md-offset-4 col-md-4" role="alert" style="color: #fff;"></b>Success! Notice Added</b></div>';
			echo '<script>setTimeout(function () { window.location.href = "notice.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-success bg-success col-md-offset-4 col-md-4" role="alert" style="color: #fff;"><b>error while adding notice</b></div>';
		}
	}

	return false;
}

/*******************************
 * delete notice.
 *******************************/

function delete_notice($notice_id,$role)
{
	global $con;
	$notice_id = $notice_id;
	$role = $role;

	if(isset($_POST['yes']))
	{
		$query = "DELETE from notice where notice_id='$notice_id'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		echo mysqli_error($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Success! Notice removed</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "notice.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>error while removing notice, try again</b></p></div>';
		}
	}
	
	return false;
}

/*******************************
 * edit notice information.
 *******************************/

function edit_notice($notice_id,$role)
{
	global $con;
	$role = $role;

	if (isset($_POST['edit_notice']))
	{
		$name = $_POST['name'];
		$name = stripslashes($name);
		$description = $_POST['description'];
		$description = stripslashes($description);
		$date = $_POST['date'];
		
		$query = "UPDATE notice SET title='$name', description='$description', date='$date' WHERE notice_id='$notice_id'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success bg-success col-md-offset-4 col-md-4" role="alert" style="color: #fff;"></b>Success! Notice Edited</b></div>';
			echo '<script>setTimeout(function () { window.location.href = "notice.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger bg-danger col-md-offset-4 col-md-4" role="alert" style="color: #fff;"></b>error while editing notice</b></div>';
		}
	}

	return false;
}

/*******************************
 * starter for every page.
 *******************************/

function starter($id,$name,$role,$pic,$last_login,$total_members,$core_members,$total_sessions,$completed_sessions)
{
	?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Club Manager - Dashboard</title>
<link rel='shortcut icon' href='favicon.ico' type='image/x-icon'/ >
<link href="css/pace-theme-corner-indicator.css" rel="stylesheet">
<script src="js/pace.min.js"></script>
<script>pace.start();</script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="css/styles.css" rel="stylesheet">
<script src="https://use.fontawesome.com/c250a4b18e.js"></script>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<b><a class="navbar-brand" href="home.php"><span>Club</span>Manager</a></b>
				<ul class="user-menu">
					<li class="dropdown pull-right">
						<a class="dropdown-toggle" data-toggle="dropdown"><img src="<?php echo $pic; ?>" class="img-responsive img-circle img-thumbnail" height="35px" width="35px"> <b id="mobhide"><?php echo $name; ?></b> <div class="btn btn-xs btn-info" id="mobhide"><?php echo $role; ?></div><span class="caret"></span></a>

						<ul class="dropdown-menu" role="menu">
							<li><a href="update_pic.php"><i class="fa fa-user" aria-hidden="true"></i> Change Profile Pic</a></li>
							<li><a href="user_settings.php?user_id=<?php echo $id; ?>"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
							<li><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>			
		</div><!-- /.container-fluid -->
	</nav><br>
		<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<form role="search" action="search.php" method="post">
			<div class="form-group">
				<input type="text" name="term" class="form-control" placeholder="Search" required>
			</div>
		</form>
		<ul class="nav menu">
			<li><a href="home.php"><i class="fa fa-tachometer" aria-hidden="true"></i>
 <b>Dashboard</b></a></li>

			<li><a href="blog-home.php"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <b>Blog</b></a></li>

			<li><a href="notice.php"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> <b>Club Notice</b></a></li>

			<li><a href="attendance.php"><i class="fa fa-line-chart" aria-hidden="true"></i> <b>Attendance</b></a></li>

			<?php if($role == 'President'){
				echo '<li><a href="manage_members.php"><i class="fa fa-users" aria-hidden="true"></i> <b>Members</b></a></li>';
			} ?>
			
			<li><a href="schedule.php"><i class="fa fa-calendar" aria-hidden="true"></i> <b>Sessions</b></a></li>

			<li role="presentation" class="divider"></li>
			<li><a style="color: #000;"><i class="fa fa-clock-o" aria-hidden="true"></i> <b>last login</b><br><?php echo $last_login; ?></a></li>
			<li role="presentation" class="divider"></li>
		</ul>
		<div class="text-center" style="margin-top: 95px; color: #000;"><b>Made with <i style="color: red;">&#10084;</i> By <a href="http://sharadshinde.in" target="blank">Sharz</a> 2016</b></div>
	</div><!--/.sidebar-->
	
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
	<?php
	return false;
}

function at_bottom()
{
	?>
	</div>	<!--/.main-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="css/DateTimePicker.min.css" />
<script type="text/javascript" src="js/DateTimePicker.min.js"></script>
<!-- include summernote css/js-->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js"></script>
	<script>
		$(document).ready(function()
		{
		    $("#dtBox").DateTimePicker();
			$('.menu').on("click",".menu",function(e){ 
  			e.preventDefault(); // cancel click
  			var page = $(this).attr('href');   
  			$('.menu').load(page);
			});
			$('#content').summernote({
    			height: 350,
   			 });
		});
	</script>
	<script>
		
		!function ($) {
		    $(document).on("click","ul.nav li.parent > a > span.icon", function(){          
		        $(this).find('em:first').toggleClass("glyphicon-minus");      
		    }); 
		    $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
		}(window.jQuery);

		$(window).on('resize', function () {
		  if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
		})
		$(window).on('resize', function () {
		  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
		})
	</script>
</body>
</html>
	<?php
	return false;
}

/**********************************************************************************
*****************************   Blog functions    *********************************
**********************************************************************************/

function show_posts($role,$session_name)
{
	global $con;
	$query = "SELECT * FROM blog_posts ORDER BY id DESC";
	$result = mysqli_query($con,$query);

	if(mysqli_num_rows($result) > 0)
	{
		$select = 1;
		while($row = mysqli_fetch_assoc($result))
		{
			if($select%2 == 1)
			{
				$css = 'panel-primary';
			}
			else
			{
				$css = 'panel-info';
			}
		?>
		<div class="col-lg-5">
			<div class="panel <?php echo $css; ?>">
			<div class="panel-heading">
			<?php echo $row['postTitle']; ?>
			</div>
			<div class="panel-body">
			<p>Posted by <b><?php echo $row['auther']; ?></b> on <b><?php echo date('jS M Y H:i:s', strtotime($row['post_date'])); ?></b> in 
			<a href="viewbycat.php?cat=<?php echo $row['catinfo']; ?>"><?php echo $row['catinfo']; ?></a>
				<br><br>
			    <p><?php echo $row['description']; ?></p>
			    </div>               
			    <div class="panel-footer">
			    <?php
			    	if($session_name == $row['auther'] || $role == 'President')
			    	{?>
			    		<a class="btn btn-warning" href="edit-post.php?id=<?php echo $row['id']; ?>&title=<?php echo $row['postTitle']; ?>">Edit</a>
			    		<a class="btn btn-danger" href="delete-post.php?id=<?php echo $row['id']; ?>&title=<?php echo $row['postTitle']; ?>">Delete</a> 
			    	<?php }
			   	?>
			    <a class="btn btn-primary" href="viewpost.php?id=<?php echo $row['id']; ?>&title=<?php echo $row['postTitle']; ?>">Read More</a>      
			    </div></div></div>
			    <?php
			    $select++;
		} // Post list while closed.		

	} // Post list if closed.
	else
	{
		echo '<div class="alert bg-warning text-center col-md-offset-4 col-md-4 col-sm-12"><span><h4>no posts found, visit after sometime!</h4></span></div>';
	}
	return false;
}

function new_post()
{
	global $con;

	$auther = $_SESSION['username'];

	if(isset($_POST['publish'])) 
	{

		$postTitle = $_POST['postTitle'];
		$postTitle = stripslashes($postTitle);
		$postTitle = mysqli_real_escape_string($con,$postTitle);

		$description = $_POST['description'];
		$description = stripslashes($description);
		$description = mysqli_real_escape_string($con,$description);

		$content = $_POST['content'];
		$content = stripslashes($content);
		$content = mysqli_real_escape_string($con,$content);

		$catvalue = $_POST['cats'];
		$catvalue = stripslashes($catvalue);

		$query = "INSERT INTO blog_posts (id, postTitle, description, content, post_date, auther, catinfo) VALUES (NULL, '$postTitle', '$description', '$content', NOW(), '$auther','$catvalue')";
		mysqli_query($con,$query);
		
		$rows = mysqli_affected_rows($con);

		if($rows == 1)
		{
			echo '<div class="text-center alert bg-success col-md-offset-4 col-md-4" role="alert"><span>Success! Post Published</span></div>';
			echo '<script>setTimeout(function () { window.location.href = "blog-home.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Sorry, error while publishing post, try again</span></div>';	
		}

	}

	return false;
}

function edit_post($post_id)
{
	global $con;
	if (isset($_POST['update'])) 
	{
		$postTitle = $_POST['postTitle'];
		$postTitle = stripslashes($postTitle);
		$postTitle = mysqli_real_escape_string($con,$postTitle);

		$description = $_POST['description'];
		$description = stripslashes($description);
		$description = mysqli_real_escape_string($con,$description);

		$content = $_POST['content'];
		$content = stripslashes($content);
		$content = mysqli_real_escape_string($con,$content);

		$catvalue = $_POST['cats'];
		$catvalue = stripslashes($catvalue);

		$query = "UPDATE blog_posts SET postTitle='$postTitle',description='$description',content='$content',post_date=NOW() ,catinfo='$catvalue' WHERE id='$post_id'";

		mysqli_query($con,$query);

		$rows = mysqli_affected_rows($con);

			if($rows == 1)
			{
				echo '<div class="text-center alert bg-success col-md-offset-4 col-md-4" role="alert"><span>Success! Post Updated</span></div>';
				echo '<script>setTimeout(function () { window.location.href = "blog-home.php";}, 1000);</script>';
			}
			else
			{
				echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Error, post updating failed, try again</span></div>';
				
			}
	}
	return false;
}

function delete_post($post_id)
{
	global $con;

	if(isset($_POST['yes']))
	{
		$query = "DELETE FROM blog_posts WHERE id='$post_id'";
		mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert bg-success col-md-offset-4 col-md-4" role="alert"><span>Success! Post Deleted</span></div>';
				echo '<script>setTimeout(function () { window.location.href = "blog-home.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Error, post updating failed, try again</span></div>';
		}
	}
	return false;
}

function show_home_posts()
{
	global $con;

	$query = "SELECT * FROM blog_posts ORDER BY id DESC LIMIT 0,5";
	$result = mysqli_query($con,$query);

	if(mysqli_num_rows($result) > 0)
	{
		$select = 1;
		while($row = mysqli_fetch_assoc($result))
		{
			if($select%2 == 1)
			{
				$css = 'panel-teal';
			}
			else
			{
				$css = 'panel-orange';
			}
			?>

			<div class="col-lg-4">
				<div class="panel <?php echo $css; ?>">
				<div class="panel-body">
				<a href="viewpost.php?id=<?php echo $row['id']; ?>&title=<?php echo $row['postTitle']; ?>" style="color: #fff;">
				<h3 style="color: #fff;"><?php echo $row['postTitle']; ?></h3>
				<a href="viewpost.php?id=<?php echo $row['id']; ?>&title=<?php echo $row['postTitle']; ?>" style="color: #fff;">
				<p>Posted by <b><?php echo $row['auther']; ?></b> on <b><?php echo date('jS M Y H:i:s', strtotime($row['post_date'])); ?></b> in 
				<b><a style="color: #fff;" href="viewbycat.php?cat=<?php echo $row['catinfo']; ?>"><?php echo $row['catinfo']; ?></a></b></p>
			    
			    <p><a style="color: #fff;" href="viewbycat.php?cat=<?php echo $row['catinfo']; ?>"><?php echo $row['description']; ?></a></p>
			    </a>
			    </a>
			    </div>               
			    </div>
			</div>
			    <?php
			    $select++;
		} // Post list while closed.		

	} // Post list if closed.
	else
	{
		echo '<div class="alert bg-warning text-center col-md-offset-4 col-md-4 col-sm-12"><span><h4>no posts found, visit after sometime!</h4></span></div>';
	}

	return false;
}