<?php


use EMicro\Application;
use EMicro\Config;
use EMicro\Loader;

session_start();

require_once "../vendor/autoload.php";

define("APP_PATH", dirname(__DIR__).'/application');
define("CONFIG_PATH", APP_PATH.'/config');

Loader::scan(APP_PATH);
Config::scan(CONFIG_PATH);
Application::scan(APP_PATH);

$handle = ($_SERVER['REQUEST_URI'] == '/' ? '/login' : $_SERVER['REQUEST_URI']);

if (!isset($_SESSION['username']) && $_SERVER['REQUEST_URI'] != '/attempt'){
    $handle = '/login';
}

if ($_SERVER['REQUEST_URI'] == '/' && isset($_SESSION['username'])){
    $handle = '/index';
}

Application::run($handle);





