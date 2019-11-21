<?php
	include("../config.php");
	//Print error msg if login fail
	$error = '';
	session_start();
	if(isset($_SESSION['id'])){
	    header("Location: ".SITEURL."php/home.php");
	}
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_POST['usr']) && isset($_POST['pw'])){
			$id = $_POST['usr'];
			$pass = $_POST['pw'];
			// Check username exists
			if(Account::getAccByID($id)){
				$account = Account::getAccByID($id);
				// Check password
				if(md5($_POST['pw']) == $account->pass){
					if($account->type == 2){
					    $_SESSION['id'] = $id;
					    header("Location: ".SITEURL."php/home.php");
					}else if($account->type == 0){
					    $_SESSION['id'] = $id;
					    header("Location: ".SITEURL."php/home.php");
					}
					else{
					     echo '<script>alert("Your account is being processed. Please wait for the administrator to verify your account.");
					     </script>';
					}
				}else{
					$error = 'Invalid username or password. Please try again.';
				}
			}else{
				$error = 'Invalid username or password. Please try again.';
			}
		}
	}
?>

<!DOCTYPE HTML5>
<html lang=en>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="../css/theme1.css">
	<script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
	<title>Software Engineering</title>
</head>
<body>
	<?php
	    include(TEMPLATE_PATH."header.php");
	?>
	
	<div id="title_bar">
		<h3 style="text-shadow: 0px 0px 10px black">Sign in to your account</h3>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="col-sm-3"></div>
			<div class="col-sm-6" id="login_container">
				<form method="POST">
					<div class="form-group">
					    <p style="color: red;"> <?php echo $error ?> <p>
						<label for="user">Student ID</label>
						<input type="text" class="form-control" name="usr" placeholder="student id">
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" class="form-control" name="pw" placeholder="password">
					</div>
					<div style="text-align: center;">
						<button type="submit" class="btn btn-default" style="border: solid; border-width: 1px; border-color: lightgray;">Login</button>
					</div>
				</form>
			</div>
			
			<div class="col-sm-3"></div>
		</div>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="col-sm-3"></div>
			<div class="col-sm-6">
				<a href="sign_up.php" class="text-decoration-none">Don't have an account?</a>
				<a href="forget_password.php" class="text-decoration-none" style="float: right;">Forgot password?</a>
			</div>
			<div class="col-sm-3"></div>
		</div>
	</div>
</body>
</html>