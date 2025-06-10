<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

use Php\PhpWebLogin\App\Router;
use Php\PhpWebLogin\Config\Database;
use Php\PhpWebLogin\Controller\HomeController;
use Php\PhpWebLogin\Controller\UserController;
use Php\PhpWebLogin\Middleware\MustLoginMiddleware;
use Php\PhpWebLogin\Middleware\MustNotLoginMiddleware;

require_once __DIR__ . "/../vendor/autoload.php";

Database::getConnection("prod");

Router::add('GET', '/', HomeController::class, 'index', []);

Router::add('GET', '/users/register', UserController::class, 'getRegister', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/register', UserController::class, 'postRegister', [MustNotLoginMiddleware::class]);

Router::add('GET', '/users/login', UserController::class, 'getLogin', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/login', UserController::class, 'postLogin', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/logout', UserController::class, 'logout', [MustLoginMiddleware::class]);

Router::add('GET', '/users/profile', UserController::class, 'getUpdateProfile', [MustLoginMiddleware::class]);
Router::add('POST', '/users/profile', UserController::class, 'postUpdateProfile', [MustLoginMiddleware::class]);

Router::add('GET', '/users/password', UserController::class, 'getUpdatePassword', [MustLoginMiddleware::class]);
Router::add('POST', '/users/password', UserController::class, 'postUpdatePassword', [MustLoginMiddleware::class]);

Router::run();