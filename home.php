<?php
	require_once('funs.php');
	session_start();
	check_session();
	$session_name = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Intern Assignment - Member Dashboard</title>
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
				<b><a class="navbar-brand" href="#">User Dashboard</a></b>
				<ul class="user-menu">
					<li><a class="btn btn-danger" href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
				</ul>
			</div>			
		</div><!-- /.container-fluid -->
	</nav>
	
		<div class="container">
			<h1 class="page-header text-center">Welcome <b><?php echo $session_name; ?></b>!</h1>
		</div>

<div class="text-center" style="margin-top: 175px; color: #000;"><b>Made with <i style="color: red;">&#10084;</i> By <a href="http://sharadshinde.in" target="blank">Sharz</a> 2016</b></div>
	<script>
		$(document).ready(function()
		{
			$('.menu').on("click",".menu",function(e){ 
				e.preventDefault(); // cancel click
				var page = $(this).attr('href');   
				$('.menu').load(page);
			});
		});
	</script>
</body>
</html>