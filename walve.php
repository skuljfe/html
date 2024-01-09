<?php
require __DIR__ . '/vendor/autoload.php';
use InfluxDB\Client;
use InfluxDB\Point;
use InfluxDB\Database;
include 'functions.php';

function insert($n){
	$host='localhost';
	$port=8086;
	$client = new Client($host, $port);
	// directly get the database object
	$database = $client->selectDB('smarthome');
	$points = array(
		new Point(
			'walve', // the name of the measurement
			$n, // measurement value
	));
	// we are writing unix timestamps, which have a second precision
	$result = $database->writePoints($points, Database::PRECISION_SECONDS);
}

function valwe($n){
	$server   = '192.168.2.207';
	$port     = 1883;
	$clientId = 'test-publisher';
	$mqtt = new \PhpMqtt\Client\MQTTClient($server, $port, $clientId);
	$mqtt->connect();
	$mqtt->publish('php-mqtt/client/test', $n, 0);
	$mqtt->close();
	sendMessage('Aktivnost zalogovnik','Uporabnik '.$_POST['user'].' je spremenil nastavitve zalogovnika.');
	insert($n);
}

valwe($_POST['vrsta'])
?>
