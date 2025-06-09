<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

use Php\PhpWebLogin\App\Router;
use Php\PhpWebLogin\Controller\HomeController;

require_once __DIR__ . "/../vendor/autoload.php";

Router::add('GET', '/', HomeController::class, 'index', []);

Router::run();