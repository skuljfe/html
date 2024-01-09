<?php
require __DIR__ . '/vendor/autoload.php';
use InfluxDB\Client;

function getdata($type, $para, $graph){
	$host='localhost';
	$port=8086;
	$client = new Client($host, $port);
	// directly get the database object
	$database = $client->selectDB('smarthome');

	if($type=='Nibe_topics'){
		if($graph==0)
			$result = $database->query("SELECT * FROM ".$para." GROUP BY * ORDER BY DESC LIMIT 1 tz('Europe/Ljubljana')");
		else
			$result = $database->query("SELECT * FROM ".$para." GROUP BY * ORDER BY DESC LIMIT 100 tz('Europe/Ljubljana')");
		}
	else{
		$database = $client->selectDB('uporabniki');
		$result = $database->query("select * from ".$para." tz('Europe/Ljubljana')");
	}
	$result = $result->getPoints();
	if(count($result)!=0){
		if($graph==0){
			return $result[0]['value'];
			
		}
		else{
			$return=array();
			$data=array();
			$time=array();
			
			foreach($result as $x){
				array_push($data, $x['value']);
				array_push($time, substr($x['time'],11,5));

			}
			$data=array_reverse($data);
			$time=array_reverse($time);
			$return[0]=$data;
			$return[1]=$time;
			return ($return);
		}
	}
	else
		return -1;
}

function getdoor(){
	$host='localhost';
	$port=8086;
	$client = new Client($host, $port);
	// directly get the database object
	$database = $client->selectDB('smarthome');
	$result = $database->query("select * from last_in  ORDER BY DESC LIMIT 4 tz('Europe/Ljubljana')");
	$result = $result->getPoints();
	return $result;
}

function getUsers(){
	$data=array();
	$host='localhost';
	$port=8086;
	$client = new Client($host, $port);
	// directly get the database object
	$database = $client->selectDB('uporabniki');
	$result = $database->query("show measurements;");
	$result = $result->getPoints();
	foreach($result as $x){
		array_push($data,$x['name']);
	}
	return ($data);
}

function getdata_all($type){
	$data=array();
	$host='localhost';
	$port=8086;
	$client = new Client($host, $port);
	// directly get the database object
	$database = $client->selectDB('smarthome');
	$result = $database->query("select value from calculated_1, calculated_2, calculated_3, real_1, real_2, real_3, return_2, return_3, watter, fan_speed, pump_speed GROUP BY * ORDER BY DESC LIMIT 1 tz('Europe/Ljubljana')");
	$result = $result->getPoints();

	foreach($result as $x){
		array_push($data,$x['value']);
	}
	return ($data);
}

function getLights(){
	$host='localhost';
	$port=8086;
	$client = new Client($host, $port);
	// directly get the database object
	$database = $client->selectDB('smarthome');
	$result = $database->query("select * from light_vhod, light_garaza, light_kuhinja, light_terasa GROUP BY * ORDER BY DESC LIMIT 1");
	$result = $result->getPoints();
	return $result;
}

function getWatter(){
	$host='localhost';
	$port=8086;
	$client = new Client($host, $port);
	// directly get the database object
	$database = $client->selectDB('smarthome');
	$result = $database->query("select * from wattertank ORDER BY DESC LIMIT 1");
	$result = $result->getPoints();
	return $result;
}

function getWalve(){
	$host='localhost';
	$port=8086;
	$client = new Client($host, $port);
	// directly get the database object
	$database = $client->selectDB('smarthome');
	$result = $database->query("select * from walve ORDER BY DESC LIMIT 1");
	$result = $result->getPoints();
	return $result;
}


function getweather(){
	$apiKey = "bf233341dc839737a1eac3236a604079";
	$cityId = "3196359";
	$googleApiUrl = "https://api.openweathermap.org/data/2.5/onecall?lat=45.858095&lon=14.643811&exclude=hourly,alerts,minutely&units=metric&appid=".$apiKey;

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);
	
	curl_close($ch);
	$response=json_decode($response);
	
	$response->current->sunrise=gmdate("H:i", $response->current->sunrise);
	$response->current->sunset=gmdate("H:i", $response->current->sunset);
	switch($response->current->weather[0]->main)
	{
		case 'Clear':$response->current->weather[0]->main="Jasno";$response->current->weather[0]->icon='sunny_light_color_96dp';break;
		case 'Thunderstorm':$response->current->weather[0]->main="Nevihta";$response->current->weather[0]->icon='strong_tstorms_light_color_96dp';break;
		case 'Drizzle':$response->current->weather[0]->main="Rosenje";$response->current->weather[0]->icon='drizzle_light_color_96dp';break;
		case 'Rain':$response->current->weather[0]->main="Dež";$response->current->weather[0]->icon='showers_rain_light_color_96dp';break;
		case 'Snow':$response->current->weather[0]->main="Sneg";$response->current->weather[0]->icon='snow_showers_snow_light_color_96dp';break;
		case 'Fog':$response->current->weather[0]->main="Megla";$response->current->weather[0]->icon='haze_fog_dust_smoke_light_color_96dp';break;
		case 'Clouds':$response->current->weather[0]->main="Oblačno";$response->current->weather[0]->icon='cloudy_light_color_96dp';break;
	}
	foreach($response->daily as $data){
		switch($data->weather[0]->main)
		{
			case 'Clear':$data->weather[0]->icon='sunny_light_color_96dp';break;
			case 'Thunderstorm':$data->weather[0]->icon='strong_tstorms_light_color_96dp';break;
			case 'Drizzle':$data->weather[0]->icon='drizzle_light_color_96dp';break;
			case 'Rain':$data->weather[0]->icon='showers_rain_light_color_96dp';break;
			case 'Snow':$data->weather[0]->icon="snow_showers_snow_light_color_96dp";break;
			case 'Fog':$data->weather[0]->icon='haze_fog_dust_smoke_light_color_96dp';break;
			case 'Clouds':$data->weather[0]->icon='cloudy_light_color_96dp';break;
		}
	}
	
	return $response;
}

function check_garbage(){
	$garbage_dates=[
		'01'=>['mesani'=>[4,18],'embalaza'=>[11,25]],
		'02'=>['mesani'=>[1,15],'embalaza'=>[8,22]],
		'03'=>['mesani'=>[1,15,29],'embalaza'=>[8,22]],
		'04'=>['mesani'=>[12,26],'embalaza'=>[5,19]],
		'05'=>['mesani'=>[10,24],'embalaza'=>[3,17,31]],
		'06'=>['mesani'=>[7,21],'embalaza'=>[14,28]],
		'07'=>['mesani'=>[5,19],'embalaza'=>[12,26]],
		'08'=>['mesani'=>[2,16,30],'embalaza'=>[9,23]],
		'09'=>['mesani'=>[13,27],'embalaza'=>[6,20]],
		'10'=>['mesani'=>[11,25],'embalaza'=>[4,18]],
		'11'=>['mesani'=>[8,22],'embalaza'=>[15,29]],
		'12'=>['mesani'=>[6,20],'embalaza'=>[13,27]],
	];
	
	if(in_array(date("d")+1, $garbage_dates[date("m")]['mesani']))
		return "mesani";
	if(in_array(date("d")+1, $garbage_dates[date("m")]['embalaza']))
		return "embalaza";
}

function sendMessage($title,$message) {
    $content      = array(
        "en" => $message
    );
	$headings = array(
        "en" => $title
    );
    $hashes_array = array();
    array_push($hashes_array, array(
        "id" => "test",
        "text" => "Več",
        "url" => "http://skulj.xyz/"
    ));
    $fields = array(
        'app_id' => "60a52fa6-410a-4e00-8ddb-38a34a14c867",
		'headings' => $headings,
        'included_segments' => array(
            'Subscribed Users'
        ),
        'contents' => $content,
        'web_buttons' => $hashes_array
    );
    
    $fields = json_encode($fields);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic ZThkMzVlM2UtMGI3NS00YzQ1LTg4OWYtMDE5MjBjMDMzNDQ0'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}


?>
