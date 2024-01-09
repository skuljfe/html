<?php
// začetek sessiona
echo ".";
session_start();
include 'functions.php';

$username = "";
$password = "";
$cre_err="";
$type='user';

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Preverim ali je ime prazno
    if(empty(trim($_POST["username"]))){
        $cre_err = "Vnesi ime.";
    } else{
        $username = trim($_POST["username"]);
    }
	// preverim ali je geslo prazno
	if(empty(trim($_POST["password"]))){
        $cre_err = "Vnesi geslo.";
    } else{
        $password = trim($_POST["password"]);
    }
	if(empty($cre_err)){
		$corect_pass=getdata($type,$username,0);
		if($corect_pass==-1){
			$cre_err = "Napacni podatki!";
		}
		else{
			if($corect_pass==$password){
				session_start();
								
				// shranjevnaje podatkov v session
				$_SESSION["loggedin"] = true;
				$_SESSION["username"] = $username;
				// preusmeri na domačo ali pa admin page, odvisno od podatkov ki smo jih vnesli
				if($username=='admin')
					header("location: admin.php");
				else
					header("location: index.php");
			}
		}
	}
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Pametna hiša</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->

<link rel="icon" type="image/png" sizes="64x64" href="img/icona.png">
<link rel="apple-touch-icon" sizes="180x180" href="img/icona.png">
<meta name="msapplication-square70x70logo" content="img/icona.png">
<meta name="msapplication-square150x150logo" content="img/icona.png">
<meta name="msapplication-square310x310logo" content="img/icona.png">
<meta name="msapplication-wide310x150logo" content="img/icona.png">
<meta name="msapplication-TileColor" content="#2e2e2e">
<meta name="apple-mobile-web-app-capable" content="yes" />
</head>
<body>  
	
<div class="limiter">
	<div class="container-login100" style="background-image: url('img/login_bg.jpg');">
		<div class="wrap-login100">
			<form class="login100-form validate-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<span class="login100-form-logo">
					<i class="zmdi zmdi-sign-in"></i>
				</span>

				<span class="login100-form-title p-b-34 p-t-27">
					Prijava
				</span>

				<div class="wrap-input100 validate-input">
					<input type="text" class="input100" value="<?php echo $username; ?>" name="username" placeholder="Uporabniško ime" autocomplete="on">
					<span class="focus-input100" data-placeholder="&#xf207;"></span>
				</div>

				<div class="wrap-input100 validate-input">
					<input class="input100" type="password" name="password" placeholder="Geslo" autocomplete="on">
					<span class="focus-input100" data-placeholder="&#xf191;"></span>
				</div>

				<div class="container-login100-form-btn">
					<button class="login100-form-btn" type="submit">
						Prijavi se
					</button>
				</div>
				<p style="text-align:center;color:white;"><?php echo $cre_err;?></p>

			</form>
		</div>
	</div>
</div>
	
<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="js-login/main.js"></script>
	
</body>
</html>