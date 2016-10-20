<?php
	require_once('funs.php');
	global $con;
	$code = $_GET['code'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Intern Assingment - Activation</title>
<link rel='shortcut icon' href='favicon.ico' type='image/x-icon'/ >
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="css/styles.css" rel="stylesheet">
</head>

<body style="overflow: hidden;background-color: #eee;">

	<div class="row">
	<h1 class="text-center" style="padding-top:25px;color: #000;font-weight: bold;font-size: 3.0em;">Intern Assignment <small>(Login Panel with Confirmation)</small></h1><br>
	
	<?php
		if(empty($code))
		{
			echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Sorry, <b>wrong confirmation code</b>, try again.</span></div>';
		}
		else
		{
			$query = "SELECT * FROM intern where code='$code'";
			$result = mysqli_query($con,$query);
			$rows = mysqli_affected_rows($con);
	
			if($rows == 1)
			{
				$query1 = "UPDATE intern SET status='1' WHERE code='$code'";
				mysqli_query($con,$query1);
				$rows1 = mysqli_affected_rows($con);
				if($rows1 == 1)
				{
					echo '<div class="text-center alert bg-success col-md-offset-4 col-md-4" role="alert"><span>Success! Your <b>Email is Confirmed!</b> Your Account will be Activate in Shortly.</span></div>';
				}
				else
				{
					echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>problem while updating user status</span></div>';
					
				}
			}
			else
			{
				echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Sorry, <b>wrong confirmation code</b>, try again.</span></div>';
			}
		}
	?>

	</div><!-- /.row -->	
	<div class="text-center" style="margin-top: 75px; color: #000;"><b>Made with <i style="color: red;">&#10084;</i> By <a href="http://sharadshinde.in" target="blank">Sharz</a> 2016</b></div>
</body>
</html>