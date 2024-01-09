<?php
require __DIR__ . '/vendor/autoload.php';
include 'functions.php';

sendMessage($_POST['title'],$_POST['message']);
?>