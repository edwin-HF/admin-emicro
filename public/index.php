<?php


use EMicro\Application;

session_start();

require_once "../vendor/autoload.php";

$application = Application::getInstance();

$capsule = new Illuminate\Database\Capsule\Manager();
$capsule->addConnection(config('database'));
$capsule->setAsGlobal();
$capsule->bootEloquent();


$handle = ($_SERVER['REQUEST_URI'] == '/' ? '/login' : $_SERVER['REQUEST_URI']);

if (!isset($_SESSION['username']) && $_SERVER['REQUEST_URI'] != '/attempt'){
    $handle = '/login';
}

if ($_SERVER['REQUEST_URI'] == '/' && isset($_SESSION['username'])){
    $handle = '/index';
}

$application->run($handle);





