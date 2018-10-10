<?php

require './CryptorgApi.php';

$api = new CryptorgApi('API_KEY', 'API_SECRET');

var_dump($api->status());