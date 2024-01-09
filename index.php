<?php
echo ".";
session_start();
if(isset($_SESSION["loggedin"])!=1 && $_SESSION["loggedin"] === false){
	header("location: login.php");
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Pametna hiša</title>
<script src="js.js" ></script>
<link href="css.css" rel="stylesheet" type="text/css" />
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1.0, user-scalable=no"> <!--viewport + izklop zooma-->

<!--ikones-->
<link rel="icon" type="image/png" sizes="64x64" href="img/icona.png">
<link rel="apple-touch-icon" sizes="180x180" href="img/icona.png">
<meta name="msapplication-square70x70logo" content="img/icona.png">
<meta name="msapplication-square150x150logo" content="img/icona.png">
<meta name="msapplication-square310x310logo" content="img/icona.png">
<meta name="msapplication-wide310x150logo" content="img/icona.png">
<meta name="msapplication-TileColor" content="#2e2e2e">
<meta name="apple-mobile-web-app-capable" content="yes" />

<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script> <!--knjužnica za obvestila-->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> <!--knjižnica grafov-->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> <!--jquery-->
<script src="./waterTank.js"></script> <!--knjižnica za zalogovnik-->

<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
<script>
  window.OneSignalDeferred = window.OneSignalDeferred || [];
  OneSignalDeferred.push(function(OneSignal) {
    OneSignal.init({
      appId: "60a52fa6-410a-4e00-8ddb-38a34a14c867",
    });
  });
</script>
</head>

<!--ZAČETEK BODY-->

<body onLoad="on_startup()">

<div class="loader">
  <img src="img/loading.gif" alt="Loading..." />
</div>

<!--Na začetku se vsi podatki prensejo iz podatkovne baze.
te podatki so:
	-vreme,
	-podatki o toplotni črpalki,
	-podatki o lučeh,
	-podatki o zalogovniku
-->
<?php
include 'functions.php';
$data=getweather();
$next1=round($data->daily[1]->temp->min).' °C / '.round($data->daily[1]->temp->max).' °C';
$next2=round($data->daily[2]->temp->min).' °C / '.round($data->daily[2]->temp->max).' °C';
$next3=round($data->daily[3]->temp->min).' °C / '.round($data->daily[3]->temp->max).' °C';

$result=getdata_all('Nibe_topics');
$garbage='';
$garbage=check_garbage();
$calculated_1=$result[10];
$calculated_2=$result[9];
$calculated_3=$result[8];
$real_1=$result[5];
$real_2=$result[4];
$real_3=$result[3];
$return_2=$result[2];
$return_3=$result[1];
$watter=$result[0];
$fan_speed_chart=$result[7];
$pump_speed_chart=$result[6];

$last_in=getdoor();

$lights=getLights();
$light_vhod=$lights[3]['value'];
$light_garaza=$lights[2]['value'];
$light_kuhinja=$lights[1]['value'];
$light_terasa=$lights[0]['value'];

$result=getWatter();
$voda=$result[0]['value'];

$result=getWalve();
$ventil=$result[0]['value'];

$real_out_chart=getdata('Nibe_topics','real_out',1);
$DM=getdata('Nibe_topics','DM',1);

?>
<!--Podatke iz php spremenjlivk prepišem v js spremenljivke -->
<script>
	var garbage="<?php echo $garbage; ?>";
	
	var user="<?php echo ($_SESSION["username"]);?>";

	var light_vhod='<?php echo $light_vhod; ?>';
	var light_garaza='<?php echo $light_garaza; ?>';
	var light_kuhinja='<?php echo $light_kuhinja; ?>';
	var light_terasa='<?php echo $light_terasa; ?>';

	var fan_speed_chart=<?php echo json_encode($fan_speed_chart);?>;
	var pump_speed_chart=<?php echo json_encode($pump_speed_chart);?>;
	var real_out_chart=<?php echo json_encode($real_out_chart);?>;
	var dm_chart=<?php echo json_encode($DM);?>;
	
	var watter=<?php echo $voda; ?>;
	
	var ventil="<?php echo $ventil; ?>";
</script>

<div class="main">
	<div class="pin-wrapper">
		<div class="pin">
		  <div class="dots">
			<div class="dot"></div>
			<div class="dot"></div>
			<div class="dot"></div>
			<div class="dot"></div>
		  </div>
		  <p>Vnesi geslo!</p>
		  <div class="numbers">
			<div class="number">1</div>
			<div class="number">2</div>
			<div class="number">3</div>
			<div class="number">4</div>
			<div class="number">5</div>
			<div class="number">6</div>
			<div class="number">7</div>
			<div class="number">8</div>
			<div class="number">9</div>
			<div class="number" onClick="hide_pass();">&#10005;</div>
		  </div>
		</div>
	</div>
	
	<div class="header">
		<a href="index.php" class="logo">Smart home automation</a>
		<div class="header-right">
		</div>
	</div>
	
	<div class="grid-container_main">
		<div class="vreme">
			<div class="uporabnik">
				<p class="title"><img src="img/user.png">Uporabnik</p>
				<div class="vsebina">
					<p><?php echo ($_SESSION["username"]);?></p>
					<button class="logout"><a href="logout.php"><img src="img/logout.png" width="100%"></a></button>
				</div>
			</div>
			<div class="future_pc">
				<div class="block">
					<p class="title" style="color:white;text-align:center;"><?php echo gmdate("d.m",$data->daily[1]->dt) ?></p>
					<div class="vsebina">
						<img src="http://www.gstatic.com/images/icons/material/apps/weather/2x/<?php echo $data->daily[1]->weather[0]->icon;?>.png">
						<p style="color:white;float:left;"><?php echo $next1 ?></p>
					</div>
				</div>
				<div class="block">
					<p class="title" style="color:white;text-align:center;"><?php echo gmdate("d.m",$data->daily[2]->dt) ?></p>
					<div class="vsebina">
						<img src="http://www.gstatic.com/images/icons/material/apps/weather/2x/<?php echo $data->daily[2]->weather[0]->icon;?>.png">
						<p style="color:white;float:left;"><?php echo $next2 ?></p>
					</div>
				</div>
				<div class="block">
					<p class="title" style="color:white;text-align:center;"><?php echo gmdate("d.m",$data->daily[3]->dt) ?></p>
					<div class="vsebina">
						<img src="http://www.gstatic.com/images/icons/material/apps/weather/2x/<?php echo $data->daily[3]->weather[0]->icon;?>.png">
						<p style="color:white;float:left;"><?php echo $next3 ?></p>
					</div>
				</div>
			</div>
		</div>
		
		<div class="menu">
			<div class="tab">
				<button style="padding-left:0;" class="tablinks" onclick="openTab(event, 'home')" id="defaultOpen"><img src="img/home.png"></button>
				<button class="tablinks" onclick="openTab(event, 'toplotna')"><img class="rotate" src="img/fan_icon.png"></button>
				<button id="tablink_voda" class="tablinks" onclick="openTab(event, 'voda')"><img src="img/watter_icon.png"></button>
				<button class="tablinks" onclick="openTab(event, 'kamere')" id="cam"><img src="img/camera_icon.png"></button>
				<button style="padding-right:0;" class="tablinks" onclick="openTab(event, 'vrata')"><img src="img/gate.png"></button>
			</div>
		</div>
		<div class="image">
			<div style="position:relative; z-index:10">
				<a onclick="show_pass(11)" class="rim-hotspot" style="width:7.7%; height:12.7%; left:51.6%; top:76.7%; position:absolute; cursor:pointer; display:block; z-index:2; overflow:hidden;"></a>
				<a onclick="show_pass(21)" class="rim-hotspot" style="width:7.6%; height:10.9%; left:5.3%; top:64.3%; position:absolute; cursor:pointer; display:block; z-index:2; overflow:hidden;"></a>
				<a onclick="show_camera()" class="rim-hotspot" style="width:5.0%; height:8.8%; left:63.6%; top:50.2%; position:absolute; cursor:pointer; display:block; z-index:2; overflow:hidden;"></a>
				<a onclick="show_camera()" class="rim-hotspot" style="width:3.8%; height:7.3%; left:17.1%; top:47.2%; position:absolute; cursor:pointer; display:block; z-index:2; overflow:hidden;"></a>
			</div>
			<div class="camera_preview">
				<a class="iks" href="javascript:void(0)" onclick="show_camera()">&times;</a>
				<!--<img style="position:relative" src="http://193.77.151.121:8081/"></img>-->
			</div>
		</div>
		
		<div class="other">
		
			<div class="tabcontent" id="home">
				<div class="grid-container_home">
				  <div class="weater">
					<div id="content">
						<div id="page1">
							<div class="current">
								<div class="image_weater">
									<img src="http://www.gstatic.com/images/icons/material/apps/weather/2x/<?php echo $data->current->weather[0]->icon; ?>.png">
								</div>
								<div class="description">
									<a id="temp_mobile"><?php echo round($data->current->temp);?> °C</a>
									<p><?php echo $data->current->weather[0]->main?></p>
									<a style="color:white;font-size:16px;opacity:70%;">Občutek kot <?php echo round($data->current->feels_like).' °C'; ?></a>
								</div>
							</div>    
						</div>
						<div id="page2">
							<div style="width:50%;float:left;text-align:left;">
								<p style="margin-bottom:10%;"><img src="img/humidity.png" style="width:20%;margin-right:10%;"><?php echo $data->current->humidity?> %<p>
								<p style="margin-bottom:10%;"><img src="img/wind.png" style="width:20%;margin-right:10%;"><?php echo $data->current->wind_speed?> km/h<p>
								<p style="margin-bottom:10%;"><img src="img/presure.png" style="width:20%;margin-right:10%;"><?php echo $data->current->pressure?> bar<p>
							</div>
							<div style="width:50%;float:right;text-align:center;">
								<p style="margin-bottom:10%;"><img src="img/sunrise.png" style="width:20%;margin-right:10%;"><?php echo $data->current->sunrise?><p>
								<p style="margin-bottom:10%;"><img src="img/sunset.png" style="width:20%;margin-right:10%;"><?php echo $data->current->sunset?><p>
							</div>
						</div>
					</div>
					<div class="future" onclick="open_weater()">
						<div class="day0">
							<p class="title" style="color:white;text-align:center;"><?php echo gmdate("d.m",$data->daily[1]->dt) ?></p>
							<div class="vsebina">
								<img src="http://www.gstatic.com/images/icons/material/apps/weather/2x/<?php echo $data->daily[1]->weather[0]->icon;?>.png">
								<p style="color:white;"><?php echo $next1 ?></p>
							</div>
						</div>
						<div class="day1" style="display:none;">
							<p class="title" style="color:white;text-align:center;"><?php echo gmdate("d.m",$data->daily[2]->dt) ?></p>
							<div class="vsebina">
								<img src="http://www.gstatic.com/images/icons/material/apps/weather/2x/<?php echo $data->daily[2]->weather[0]->icon;?>.png">
								<p style="color:white;"><?php echo $next2 ?></p>
							</div>
						</div>
						<div class="day2" style="display:none;">
							<p class="title" style="color:white;text-align:center;"><?php echo gmdate("d.m",$data->daily[3]->dt) ?></p>
							<div class="vsebina">
								<img src="http://www.gstatic.com/images/icons/material/apps/weather/2x/<?php echo $data->daily[3]->weather[0]->icon;?>.png">
								<p style="color:white;"><?php echo $next3 ?></p>
							</div>
						</div>
					</div>
					
				  </div>
				  <div class="info">
					<div class="info1" onclick="openTab(event, 'toplotna')">
						<p class="title"><img src="img/heating.png">Ogrevanje</p>
						<div class="vsebina">
							<p><img class="icon" src="img/radiator.png"><?php echo $real_1; ?> °C</p>
							<p><img class="icon" src="img/up.png"><?php echo $real_2; ?> °C</p>
							<p><img class="icon" src="img/down.png"><?php echo $real_3; ?> °C</p>
						</div>
					</div>
					<div class="info2" onclick="openTab(event, 'voda')">
						<p class="title"><img src="img/watter_icon.png">Zalogovnik</p>
						<div class="vsebina">
							<p id="procenti"><?php echo $voda; ?> %</p>
							<p id="volumen"><?php echo ($voda/100)*36000; ?> l</p>
						</div>
					</div>
					<div class="info3" onclick="openTab(event, 'vrata')">
						<p class="title"><img src="img/gate.png">Vrata</p>
						<div class="vsebina">
							<p><img class="icon" src="img/user.png"><?php echo $last_in[0]['uporabnik']?></p>
							<p><img class="icon" src="img/info.png"><?php echo substr($last_in[0]['time'],11,5);?></p>
						</div>
					</div>
				  </div>
				  <div class="biger">
					<div class="lights">
						<p class="title">Razsvetljava</p>
						<div class="vsebina">
							<div class="grid-container_lights">
							  <div class="vhod"><button onclick="light('vhod','8caab55de194')"><img src="/img/light_off.png" id="vhod" width="30%"><p style="margin:0;">Vhod</p></button></div>
							  <div class="garaza"><button onclick="light('garaza','8caab55e03a9')"><img src="/img/light_off.png" id="garaza" width="30%"><p style="margin:0;">Garaža</p></button></div>
							  <div class="kuhinja"><button onclick="light('kuhinja','E8db84d76738')"><img src="/img/light_off.png" id="kuhinja" width="30%"><p style="margin:0;">Kuhinja</p></button></div>
							  <div class="terasa"><button onclick="light('terasa','E8db84d766e1')"><img src="/img/light_off.png" id="terasa" width="30%"><p style="margin:0;">Terasa</p></button></div>
							</div>
						</div>
					</div>
					<div class="profile">
						<div class="current_pc">
							<p class="title" style="color:white;">Vreme</p>
							<div class="vsebina" style="display: flex;">
								<div class="image_weater" width="45%">
									<img src="http://www.gstatic.com/images/icons/material/apps/weather/2x/<?php echo $data->current->weather[0]->icon; ?>.png">
								</div>
								<div class="description">
									<p id="temp_mobile"><?php echo round($data->current->temp);?> °C</p>
									<p><?php echo $data->current->weather[0]->main?></p>
									<p style="color:white;font-size:16px;opacity:70%;">Občutek kot <?php echo round($data->current->feels_like).' °C'; ?></p>
								</div>
							</div>
						</div>
						<div class="current_mobile">
							<p class="title" style="color:white;"><img src="img/user.png">Uporabnik</p>
							<div class="vsebina">
								<p style="display:block;margin:auto;color:white;"><?php echo ($_SESSION["username"]);?></p>
								<button class="logout" style="display:block;margin:auto;width:50%;"><a href="logout.php"><img src="img/logout.png" width="100%"></a></button>
							</div>
						</div>
					</div>
				  </div>
				  <div class="door">
					<p class="title"><img class="indicator" src="img/green.png">Odpiranje vrat</p>
					<div class="vsebina" style="display:flex;position:relative">
						<div style="display:flex;position:absolute;z-index:1;">
							<div style="width:50%;border-right: #00000040 1px solid;text-align:center;" onclick="slide(1)">
								<img src="img/gate.png" style="width:35%;height:auto;">
								<p>Vrata 1</p>
							</div>
							<div style="width:50%;text-align:center;" onclick="slide(2)">
								<img src="img/gate.png" style="width:35%;height:auto;">
								<p>Vrata 2</p>
							</div>
						</div>
						<div id="choser">
							<div style="width:50%;border-right: #00000040 1px solid;text-align:center;" onclick="show_pass(2)">
								<img src="img/gate.png" style="width:35%;height:auto;">
								<p>Delno</p>
							</div>
							<div style="width:50%;text-align:center;" onclick="show_pass(1)">
								<img src="img/gate.png" style="width:35%;height:auto;">
								<p>Popolno</p>
							</div>
						</div>
						<div id="garbage">
							<img id="typeOfGarabe" class="blink_me" src="img/smeti.jpg" onclick="close_garbage()" width="100%">
						</div>
					</div>
					
				  </div>
				</div>
			</div>
			
			<div class="tabcontent" id="toplotna">
				<div class="grid-container_toplotna">
				  <div class="info">
					<div class="info1">
						<p class="title">Pritličje</p>
						<div class="vsebina">
							<p><img class="icon" src="img/calculated.png"><?php echo $calculated_3; ?> °C</p>
							<p><img class="icon" src="img/hot.png"><?php echo $real_3; ?> °C</p>
							<p><img class="icon" src="img/cold.png"><?php echo $return_3; ?> °C</p>
						</div>
					</div>
					<div class="info2">
						<p class="title">Mansarda</p>
						<div class="vsebina">
							<p><img class="icon" src="img/calculated.png"><?php echo $calculated_2; ?> °C</p>
							<p><img class="icon" src="img/hot.png"><?php echo $real_2; ?> °C</p>
							<p><img class="icon" src="img/cold.png"><?php echo $return_2; ?> °C</p>
						</div>
					</div>
					<div class="info3">
						<p class="title">Voda</p>
						<div class="vsebina">
							<p><img class="icon" src="img/info.png">Smart</p>
							<p><img class="icon" src="img/watter_hot.png"><?php echo $watter; ?> °C</p>
						</div>
					</div>
				  </div>
				  <div class="chart">
					<p class="title" style="height:10%;display: block; float: left;width: 50%;">Zunanja temperatura</p>
					<p class="title_right">Temperatura: <?php echo end($real_out_chart[0]);?> °C</p>
					<div class="vsebina" style="height:90%;">
						<div class="chart_temp" width="100%"></div>
					</div>
				  </div>
				  <div class="chartinfo">
					<div class="moreinfo">
						<p class="title" style="height:7.5%;margin-bottom:0;">Info</p>
						<div class="vsebina" style="height:90%;">
							<div class="chart_fan_speed" width="100%" height="50%"></div>
							<p>Ventialtor</p>
							<div class="chart_pump_speed" width="100%" height="50%"></div>
							<p>Črpalka</p>
						</div>
					</div>
					<div class="chart1">
						<p class="title" style="height:10%;display: block; float: left;width: 50%;">DM</p>
						<p class="title_right">DM: <?php echo end($DM[0]);?></p>
						<p class="title_right">Vklop pri -60 DM</p>
						<div class="vsebina" style="height:80%;">
							<div class="chart_dm" width="100%" height="100%"></div>
						</div>
					</div>
				  </div>
				</div>
				
			</div>
			
			
			<div class="tabcontent" id="voda">
				<div class="grid-container_zalogovnik">
				<div class="info">
					<div class="info1">
						<p class="title">Količina</p>
						<div class="vsebina">
							<p><?php echo ($voda/100)*36000; ?> l</p>
							<p><?php echo $voda; ?> %</p>
						</div>
					</div>
					<div class="info2">
						<p class="title">Poraba danes</p>
						<div class="vsebina">
							<p>55 l</p>
						</div>
					</div>
					<div class="info3" onclick="walve('kill')" style="box-shadow: 0 4px 8px 0 rgb(218, 68, 83);background-color:#da4453b8;">
						<p class="title" style="color:white;">Izklop</p>
						<div class="vsebina">
							<img id="kill" src="img/kill.png" style="width:50%;display:block;margin:auto;">
						</div>
					</div>
				</div>
				<div class="vreme_zalogovnik">
					<div style="width:33%">
							<p class="title"><?php echo gmdate("d.m",$data->daily[1]->dt) ?></p>
							<div class="vsebina">
								<img src="http://www.gstatic.com/images/icons/material/apps/weather/2x/<?php echo $data->daily[1]->weather[0]->icon;?>.png">
								<p><?php echo $next1 ?></p>
							</div>
					</div>
					<div style="width:33%">
						<p class="title"><?php echo gmdate("d.m",$data->daily[2]->dt) ?></p>
						<div class="vsebina">
							<img src="http://www.gstatic.com/images/icons/material/apps/weather/2x/<?php echo $data->daily[2]->weather[0]->icon;?>.png">
							<p><?php echo $next2 ?></p>
						</div>
					</div>
					<div style="width:33%">
						<p class="title"><?php echo gmdate("d.m",$data->daily[3]->dt) ?></p>
						<div class="vsebina">
							<img src="http://www.gstatic.com/images/icons/material/apps/weather/2x/<?php echo $data->daily[3]->weather[0]->icon;?>.png">
							<p><?php echo $next3 ?></p>
						</div>
					</div>
				</div>
				  <div class="graphbutton">
					<div class="voda">
						<div class="waterTankHere1"></div>
					</div>
					<div class="vklopizklop">
						<p class="title" style="height:10%;">Upravljanje</p>
						<div class="vsebina" style="height:90%;">
							<div class="pipe2" onclick="walve('half')"><img id="half" src="img/pipe_on.jpg" width="50%"><p>Del sistema</p></div>
							<div class="pipe1" onclick="walve('full')"><img id="full" src="img/pipe.jpg" width="50%"><p>Celoten sistem</p></div>
						</div>
					</div>
				  </div>
				</div>
				
			</div>
			
			<div class="tabcontent" id="kamere">
				<!--
				<img class="camera" src="http://193.77.151.121:8081/"></img>
				<img class="camera" src="http://193.77.151.121:8081/"></img>
-->
			</div>
			
			<div class="tabcontent" id="vrata">
				<div class="grid-container_vrata">
					<div class="door">
						<p class="title"><img class="indicator" src="img/green.png">Odpiranje vrat</p>
						<div class="vsebina" style="display:flex">
							<div style="width:50%;border-right: #00000040 1px solid;text-align:center;" onclick="show_pass(11)">
								<img src="img/gate.png" style="width:35%;height:auto;">
								<p>Vrata 1</p>
							</div>
							<div style="width:50%;text-align:center;" onclick="show_pass(11)">
								<img src="img/gate.png" style="width:35%;height:auto;">
								<p>Vrata 2</p>
							</div>
						</div>
					</div>
					<div class="last_in">
						<p class="title" style="padding:2%;">Zgodovina</p>
						<div class="vsebina" style="display:flex;align-items: unset;">
<!--
							<div class="door_block">
								<p><img class="icon" src="img/user.png"><?php echo $last_in[0]['uporabnik']?></p>
								<p>Vrata <?php echo $last_in[0]['value']?></p>
								<p><img class="icon" src="img/koledar.png"><?php echo date("d.m", strtotime(substr($last_in[0]['time'],0,10)));?></p>
								<p><img class="icon" src="img/time.png"><?php echo substr($last_in[0]['time'],11,5);?></p>
							</div>
							<div class="door_block">
								<p><img class="icon" src="img/user.png"><?php echo $last_in[1]['uporabnik']?></p>
								<p>Vrata <?php echo $last_in[1]['value']?></p>
								<p><img class="icon" src="img/koledar.png"><?php echo date("d.m", strtotime(substr($last_in[1]['time'],0,10)));?></p>
								<p><img class="icon" src="img/time.png"><?php echo substr($last_in[1]['time'],11,5);?></p>
							</div>
							<div class="door_block">
								<p><img class="icon" src="img/user.png"><?php echo $last_in[2]['uporabnik']?></p>
								<p>Vrata <?php echo $last_in[2]['value']?></p>
								<p><img class="icon" src="img/koledar.png"><?php echo date("d.m", strtotime(substr($last_in[2]['time'],0,10)));?></p>
								<p><img class="icon" src="img/time.png"><?php echo substr($last_in[2]['time'],11,5);?></p>
							</div>
							<div class="door_block" style="border-right:none;">
								<p><img class="icon" src="img/user.png"><?php echo $last_in[3]['uporabnik']?></p>
								<p>Vrata <?php echo $last_in[3]['value']?></p>
								<p><img class="icon" src="img/koledar.png"><?php echo date("d.m", strtotime(substr($last_in[3]['time'],0,10)));?></p>
								<p><img class="icon" src="img/time.png"><?php echo substr($last_in[3]['time'],11,5);?></p>
							</div>
-->
						</div>
					</div>
				</div>
			</div>
			
			<div class="menu_mobile">
				<div class="tab" id="tab_mobile">
					<button style="padding-left:0;" class="tablinks" onclick="openTab(event, 'home')" id="defaultOpen"><img src="img/home.png"></button>
					<button class="tablinks" onclick="openTab(event, 'toplotna')"><img class="rotate" src="img/fan_icon.png"></button>
					<button class="tablinks" onclick="openTab(event, 'voda')"><img src="img/watter_icon.png"></button>
					<button class="tablinks" onclick="openTab(event, 'kamere')" id="cam"><img src="img/camera_icon.png"></button>
					<button style="padding-right:0;" class="tablinks" onclick="openTab(event, 'vrata')"><img src="img/gate.png"></button>
				</div>
			</div>
			
		</div>
	</div>
</div>

<script>declare();</script>

</body>

<!--KOENC BODY-->

</html>
