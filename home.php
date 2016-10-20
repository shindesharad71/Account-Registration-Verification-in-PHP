<?php
	require_once('funs.php');
	session_start();
	check_session();
	$session_name = $_SESSION['username'];
	$upload = "";
	$query = "SELECT upload from intern where username ='$session_name'";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);
	while($row = mysqli_fetch_assoc($result))
	{
		$upload = $row['upload'];
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Intern Assignment - Member Dashboard</title>
<link rel='shortcut icon' href='favicon.ico' type='image/x-icon'/ >
<link href="css/bootstrap.min.css" rel="stylesheet">
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
		<div class="error">
			<?php update_pic($session_name); ?>
		</div>
		<div class="container">
			<div class="col-md-4">
			<div class="panel panel-info">
				<div class="panel-heading">
					Upload Image
				</div>
			<div class="panel-body">
				<form action="" role="form" method="POST" class="form-signin" enctype="multipart/form-data">
					<label for="file">Select Image</label><br>
	         		<input type="file" name="image"><br>
	         </div>
			<div class="panel-footer">
	         	<button class="btn btn-primary" name="add_event" type="submit">Upload</button>
			</div>
      			</form>
			</div>
		</div>
		<div class="col-md-8">
			<div class="panel panel-info">
				<div class="panel-heading">
					Uploads
				</div>
				<div class="panel-body">
					<h3 class="text-center">Recent Upload</h3>
					<div class="col-md-offset-1 col-md-6">
						<?php
						if(empty($upload))
						{
							echo '<h4 class="text"><i>You not uploaded anything yet</i></h4>';
						}
						else
						{
							echo '<img class="img-responsive" src="'.$upload.'" alt="image">';
						}
						?>
					</div>
				</div>
			</div>
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
<?php

function update_pic($session_name)
{
	global $con;
	if(isset($_FILES['image']))
	{
      $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size =$_FILES['image']['size'];
      $file_tmp =$_FILES['image']['tmp_name'];
      $file_type=$_FILES['image']['type'];
      
      if($file_size > 2097152)
      {
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true)
      {
        move_uploaded_file($file_tmp,"imgs/".$file_name);
        $addr = 'imgs/'.$file_name;
  		$query = "UPDATE intern SET upload='$addr' WHERE username='$session_name'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		
		if($rows == 1)
		{
			echo '<div class="text-center alert bg-success col-md-offset-4 col-md-4" role="alert"><span>Success! <b>Image uploaded</b></span></div>';
			echo '<script>setTimeout(function () { window.location.href = "home.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Problem while uploading images</span></div>';	
		}
 		
    }
	else
  	{
    	print_r($errors);
  	}
	
	return false;
}
}