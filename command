#!/usr/bin/env php
<?php


use EMicro\Command;
use EMicro\Loader;
use EMicro\Config;

require_once "vendor/autoload.php";

define("APP_PATH", __DIR__ . '/application');
define("CONFIG_PATH", APP_PATH . '/config');
define("COMMAND_PATH", APP_PATH . '/command');

Loader::scan(APP_PATH);
Config::scan(CONFIG_PATH);
Command::scan(COMMAND_PATH);

$params = array_slice($_SERVER['argv'],2,$_SERVER['argc'] - 2);

try {
    Command::run($_SERVER['argv'][1], $params);
}catch (Exception $exception){
    die($exception->getMessage()."\n");
}
