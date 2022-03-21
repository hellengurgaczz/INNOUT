<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require('./src/config/config.php');

# echo $_SERVER['REQUEST_URI']; // qual URL estamos a partir do local => index.php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri === '/' || $uri == '' || $uri == '/index.php' || $uri == '/day_records.php') {
    $uri = CONTROLLER_PATH . "/day_records.php";
} else {
    $uri = CONTROLLER_PATH . $uri;
}

// } elseif ($uri == '/login.php') {
//     $uri = CONTROLLER_PATH . "/login.php";
// } elseif($uri == '/logout.php'){
//     $uri = CONTROLLER_PATH . "/logout.php";
// } elseif($uri == '/data_generator.php'){
//     $uri = CONTROLLER_PATH . "/data_generator.php";
// } elseif($uri == '/innout.php'){
//     $uri = CONTROLLER_PATH . $uri;
// }

require($uri);
