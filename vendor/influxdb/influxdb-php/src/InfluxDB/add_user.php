<?php
require __DIR__ . '/vendor/autoload.php';
use InfluxDB\Client;
use InfluxDB\Point;
$host='localhost';
$port=8086;
$client = new Client($host, $port);
// directly get the database object
$database = $client->selectDB('smarthome');
$points = array(
	new Point(
		'test_metric', // name of the measurement
		0.64, // the measurement value
));

// we are writing unix timestamps, which have a second precision
$result = $database->writePoints($points, Database::PRECISION_SECONDS);
echo $result;
?>