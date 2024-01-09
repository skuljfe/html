<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
use InfluxDB\Client;
use InfluxDB\Point;
use InfluxDB\Database;
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	
}
else{
	header("location: login.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$host='localhost';
	$port=8086;
	$client = new Client($host, $port);
	if(isset($_POST["kolicina"])){
		$database = $client->selectDB('smarthome');
		$points = array(
			new Point(
				"wattertank",
				$_POST["kolicina"],
		));
		$result = $database->writePoints($points, Database::PRECISION_SECONDS);
	}
	if(isset($_POST["username"])){
		$database = $client->selectDB('uporabniki');
		$points = array(
			new Point(
				$_POST["username"],
				$_POST["password"],
		));
		$result = $database->writePoints($points, Database::PRECISION_SECONDS);
	}
	if(isset($_POST["drop"])){
		$database = $client->selectDB('uporabniki');
		$result = $database->query("drop measurement ".$_POST["drop"].";");
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin page</title>
<link rel="stylesheet" href="css.css">
<script src="js.js"></script>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1.0, user-scalable=no"> <!--viewport + izklop zooma-->

<!--ikone-->
<link rel="icon" type="image/png" sizes="64x64" href="img/icona.png">
<link rel="apple-touch-icon" sizes="180x180" href="img/icona.png">
<meta name="msapplication-square70x70logo" content="img/icona.png">
<meta name="msapplication-square150x150logo" content="img/icona.png">
<meta name="msapplication-square310x310logo" content="img/icona.png">
<meta name="msapplication-wide310x150logo" content="img/icona.png">
<meta name="msapplication-TileColor" content="#2e2e2e">
<meta name="apple-mobile-web-app-capable" content="yes" />
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script> <!--jquery-->
</head>
<body>

<div class="loader">
  <img src="img/loading.gif" alt="Loading..." />
</div>

<?php
include 'functions.php';
$users=getUsers();
?>

<script>
	var uporabniki=<?php echo json_encode($users); ?>;
</script>

<div class="header">
	<a class="logo">Smart home automation</a>
	<a href="logout.php" style="text-align:right;">Odjava</a>
</div>

<div class="main" style="padding:2%;padding-top:0%;height:92.5%">
	<div class="control">
		<div class="block_admin">
			<p class="title"><img src="img/user.png">Dodajanje uporabnika</p>
			<div class="vsebina">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method='post'>
				  <label for="username">Uporabniško ime:</label><br>
				  <input type="text" name="username" placeholder="Vnesi ime"><br>
				  <label for="password">Geslo:</label><br>
				  <input type="text" name="password" placeholder="Vnesi geslo"><br><br>
				  <input type="submit" value="Shrani"><br>
				</form>
			</div>
		</div>
		
		<div class="block_admin">
			<p class="title"><img src="img/watter_icon.png">Spremembna količine vode</p>
			<div class="vsebina">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method='post'>
				  <label for="kolicina">Količina vode:</label><br>
				  <input type="text" name="kolicina" placeholder="Vnesi količino"><br><br>
				  <input type="submit" value="Shrani"><br>
				</form>
			</div>
		</div>
		
		<div class="block_admin">
			<p class="title"><img src="img/noti.png">Obvestila</p>
			<div class="vsebina">
				<button onclick="sendmessage('Alarm zalohovnik','Pozor količina vode v zalogovniku je zelo majhna. Zalogovnik se bo odklopil')">Alarm zalogovnik</button>
				<button onclick="sendmessage('Aktivnost vrata 1','Uporabnik Anze je odpr vrata 1')">Vrata 1</button>
			</div>
		</div>
		
		<div class="block_admin">
			<p class="title"><img src="img/noti.png">Izbirs</p>
			<div class="vsebina">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method='post'>
				  <label for="drop">Ime uporabnika:</label><br>
				  <input type="text" name="drop" placeholder="Vnesi ime"><br><br>
				  <input type="submit" value="Shrani"><br>
				</form>
			</div>
		</div>
	</div>
	<div class="block_admin" style="margin-inline:0;height:50%;margin-top: 2%;">
		<p class="title"><img src="img/noti.png">Uporabniki v bazi</p>
		<div class="vsebina">
			<p id="users"></p>
		</div>
	</div>
</div>

<script>
	loadusers(uporabniki);
	document.getElementsByClassName("loader")[0].style.display='none';
</script>
</body>
</html>