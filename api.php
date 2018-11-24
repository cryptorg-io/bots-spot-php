<?php

require './CryptorgApi.php';

$api = new CryptorgApi('API_KEY', 'API_SECRET');

$end = new DateTime();
$start = new DateTime();
$start->modify('- 6 month');

var_dump($api->getAnalytics(['start' => 1535760000, 'end' => 1543050141]));