<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

use Php\PhpWebLogin\App\Router;
use Php\PhpWebLogin\Config\Database;
use Php\PhpWebLogin\Controller\HomeController;
use Php\PhpWebLogin\Controller\UserController;

require_once __DIR__ . "/../vendor/autoload.php";

Database::getConnection("prod");

Router::add('GET', '/', HomeController::class, 'index', []);
Router::add('GET', '/users/register', UserController::class, 'getRegister', []);
Router::add('POST', '/users/register', UserController::class, 'postRegister', []);

Router::add('GET', '/users/login', UserController::class, 'getLogin', []);
Router::add('POST', '/users/login', UserController::class, 'postLogin', []);

Router::run();