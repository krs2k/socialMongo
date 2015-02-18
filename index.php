<?php
session_start();
require_once __DIR__.'/core/core.php';

MongoDriver::$dbHost = dbHost;
MongoDriver::$dbName = dbName;
MongoDriver::$dbUser = dbUser;
MongoDriver::$dbPass = dbPass;
Model::$mongoDriver = MongoDriver::getInstance();

$router = Router::getInstance();
$router->run();
?>
