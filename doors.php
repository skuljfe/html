<?php
require __DIR__ . '/vendor/autoload.php';
use InfluxDB\Client;
use InfluxDB\Point;
use InfluxDB\Database;
include 'functions.php';

function lastin($n, $user){
	$host='localhost';
	$port=8086;
	$client = new Client($host, $port);
	// directly get the database object
	$database = $client->selectDB('smarthome');
	$points = array(
		new Point(
			'last_in', // the name of the measurement
			$n, // measurement value
			['uporabnik' => $user], // measurement tags
	));

	// we are writing unix timestamps, which have a second precision
	$result = $database->writePoints($points, Database::PRECISION_SECONDS);
}

function openDoor($n,$m,$user){
	$server   = '192.168.2.207';
	$port     = 1883;
	$clientId = 'test-publisher';
	$mqtt = new \PhpMqtt\Client\MQTTClient($server, $port, $clientId);
	$mqtt->connect();
	$mqtt->publish('php-mqtt/client/test', $n.$m, 0);
	$mqtt->close();
	sendMessage('Aktivnost vrata '.$n,'Uporabnik '.$user.' je odprl vrata '.$n);
	lastin($n, $user);
}

openDoor($_POST['door'],$_POST['velikost'],$_POST['user'])
?>
