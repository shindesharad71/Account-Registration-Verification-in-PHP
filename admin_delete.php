<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Intern Assignment - Delete Member</title>
<link rel='shortcut icon' href='favicon.ico' type='image/x-icon'/ >
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
				<b><a class="navbar-brand" href="#">Admin Dashboard</a></b>
				<ul class="user-menu">
					<li><a class="btn btn-danger" href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
				</ul>
			</div>			
		</div><!-- /.container-fluid -->
	</nav>
<?php
	require_once('funs.php');
	session_start();
	check_session();
	$id = $_GET['id'];
	$session_name = $_SESSION['username'];
	
	if($session_name != 'admin')
	{
		echo '<div class="text-center alert bg-warning col-md-offset-4 col-md-4"><p><b>Access Forbidden</b></p></div>';
		echo '<script>setTimeout(function () { window.location.href = "index.php";}, 1000);</script>';
		exit();
	}
	?>

		<div class="container">
			<h1 class="page-header">Delete Account</h1>
		</div><!--/.row-->

		<div class="container">
			<div class="error">
				<?php delete_user($id); ?>
			</div>
			<div class="col-md-offset-2 col-md-6">
				<div class="panel panel-danger">
					<div class="panel-heading">
						Warning
					</div>
					<div class="panel-body">
						<form class="" method="post" action="">
							<label for="ask">Are you really want to Delete this Account?</label>
							<br>
					<div class="panel-footer">
						<div class="pull-right">
							<button class="btn btn-danger" name="yes" type="submit" id="login">Yes</button>&nbsp;&nbsp;
							<a href="admin.php" class="btn btn-default" id="login">No, go back!</a>
						</div>
					</div>
						</form>
					</div>
				</div>
			</div>
		</div><!--/.row-->
<div class="text-center" style="margin-top: 175px; color: #000;"><b>Made with <i style="color: red;">&#10084;</i> By <a href="http://sharadshinde.in" target="blank">Sharz</a> 2016</b></div>