<?php
session_start();
include_once('connection.php');
//check if user session already set then redirect to home page 
if(isset($_SESSION['userdata']) && !empty($_SESSION['userdata'])){
	header('Location:'.SITE_URL.'dashboard/home.php');
 }

// phpinfo();

//check if register form is posted
if(isset($_POST['register'])){
	$dob = str_replace('/','-', $_POST['dob']); //replace date slash chracter with highen
	$dob = date('Y-m-d H:i:s',strtotime($dob)); //set date format in DB format

	//document array
	$document = array( 
		"username" => $_POST['username'], 
		"email" => $_POST['email'], 
		"role" => "normal_user",
		"dob" => new MongoDB\BSON\UTCDateTime(strtotime($dob) * 1000),
		"gender" => $_POST['gender'],
		"password" => md5($_POST['password']),
		"create_date" => new MongoDB\BSON\UTCDateTime(time() * 1000)
	);
  	//insert query
	$insertData = $user_collection->insertOne($document);

	//check if data is inserted then redirect to success
	if($insertData->getInsertedCount() > 0){
		header('Location:'.SITE_URL.'?msg=success');
	}else{
		header('Location:'.SITE_URL.'?msg=error');
	}
}
//check if login form is submitted
if(isset($_POST['login'])){
	$login_email = $_POST['login_email'];
	$login_password = $_POST['login_password'];
	$user = $user_collection->findOne(["email" => $login_email, "password" => md5($login_password)]);
	
	if(!empty($user)){
		/* storee user info in session*/
		$_SESSION['userdata']['username'] = $user->username;
		$_SESSION['userdata']['email'] = $user->email;
		$_SESSION['userdata']['dob'] = $user->dob->toDateTime();
		$_SESSION['userdata']['gender'] = $user->gender;
		$_SESSION['userdata']['role'] = $user->role;
		$_SESSION['userdata']['create_date'] = $user->create_date->toDateTime();
	 	
	 	//redirect to home page hon success
		header('Location:'.SITE_URL.'dashboard/home.php');
	}else{
		header('Location:'.SITE_URL.'?login=failed');
	}
}
?>

<!DOCTYPE html>
<html>	
<head>
	<meta charset="utf-8">
	<title>Form-v8 by Colorlib</title>
	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<!-- Font-->
	<link rel="stylesheet" type="text/css" href="css/sourcesanspro-font.css">
	<!-- Main Style Css -->
    <link rel="stylesheet" href="css/style.css"/>
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body class="form-v8">

	<?php if(isset($_GET['msg']) && $_GET['msg']  == 'success') {?>
		<div class="alert alert-success alert-dismissible fade in">
		    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		  <strong>Congratulatons!</strong> You have been registred successfully!
		</div>			
	<?php } ?>

	<?php if(isset($_GET['msg']) && $_GET['msg']  == 'error') {?>
		<div class="alert alert-danger alert-dismissible fade in">
		    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		  	<strong>Warning!</strong>Something went wrong. Please try again!
	  	</div>	
	<?php } ?>
	<?php if(isset($_GET['login']) && $_GET['login']  == 'failed') {?>

	  	<div class="alert alert-danger alert-dismissible fade in">
		    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		  	<strong>Warning!</strong> Please check your email or password and try again!
	  	</div>			
	<?php } ?>
	<div class="page-content">

		<div class="form-v8-content">

			<div class="form-right">
				<div class="tab">
					<div class="tab-inner">
						<button class="tablinks" onclick="openCity(event, 'sign-up')" id="defaultOpen">Sign Up</button>
					</div>
					<div class="tab-inner">
						<button class="tablinks" onclick="openCity(event, 'sign-in')">Sign In</button>
					</div>
				</div>
				<form class="form-detail register" method="post">
					<div class="tabcontent" id="sign-up">
						<div class="form-row">
							<label class="form-row-inner">Username</label>
							<input type="text" name="username" id="full_name" class="input-text" required>
	  						<span class="border"></span>
						</div>
						<div class="form-row">
							<label class="form-row-inner">Date of Birth</label>
							<input type="date" name="dob" id="dob" class="input-text" required>
	  						<span class="border"></span>
						</div>
						<div class="form-row" style="margin-bottom: 30px;">
							<label class="form-row-inner" style="margin-bottom: 10px;">Gender</label>
							<input type="radio" name="gender" value="male" required>Male
							<input type="radio" name="gender" value="female" required>Female
						</div>
						<div class="form-row">
							<label class="form-row-inner">E-Mail</label>
							<input type="text" name="email" id="your_email" class="input-text" required>
	  						<span class="border"></span>
						</div>
						<div class="form-row">
							<label class="form-row-inner">Password</label>
							<input type="password" name="password" id="password" class="input-text" required>
							<span class="border"></span>
						</div>
						<div class="form-row-last">
							<input type="submit" name="register" class="register" value="Register">
						</div>
					</div>
				</form>
				<form class="form-detail login" method="post">
					<div class="tabcontent" id="sign-in">
						<div class="form-row">
							<label class="form-row-inner">E-Mail</label>
							<input type="email" name="login_email" id="your_email_1" class="input-text" required>
	  						<span class="border"></span>
						</div>
						<div class="form-row">
							<label class="form-row-inner">Password</label>
							<input type="password" name="login_password" id="password_1" class="input-text" required>
							<span class="border"></span>
						</div>
						<div class="form-row-last">
							<input type="submit" name="login" class="register" value="Sign In">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		function openCity(evt, cityName) {
		    var i, tabcontent, tablinks;
		    tabcontent = document.getElementsByClassName("tabcontent");
		    for (i = 0; i < tabcontent.length; i++) {
		        tabcontent[i].style.display = "none";
		    }
		    tablinks = document.getElementsByClassName("tablinks");
		    for (i = 0; i < tablinks.length; i++) {
		        tablinks[i].className = tablinks[i].className.replace(" active", "");
		    }
		    document.getElementById(cityName).style.display = "block";
		    evt.currentTarget.className += " active";
		}

		// Get the element with id="defaultOpen" and click on it
		document.getElementById("defaultOpen").click();
	</script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>