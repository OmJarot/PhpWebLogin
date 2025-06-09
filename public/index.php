<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

use Php\PhpWebLogin\App\Router;
use Php\PhpWebLogin\Controller\HomeController;
use Php\PhpWebLogin\Controller\ProductController;
use Php\PhpWebLogin\Middleware\AuthMiddleware;

require_once __DIR__ . "/../vendor/autoload.php";

//ROUTER Complex
//registrasi
Router::add('GET', '/', HomeController::class, 'index');
Router::add('GET', '/hello', HomeController::class, 'hello',[AuthMiddleware::class]);
Router::add('GET', '/world', HomeController::class, 'world',[AuthMiddleware::class]);
Router::add('GET', '/about', HomeController::class, 'about');

Router::add('GET', '/products/([0-9a-zA-Z]*)/categories/([0-9a-zA-Z]*)',ProductController::class, 'categories');

//jalankan router
Router::run();

//ROUTING SEDERHANA
//$path = "/index";//default path
//if (isset($_SERVER['PATH_INFO'])){//jika pathnya di set
//    $path = $_SERVER['PATH_INFO'];//akan mengganti pathnya
//}
//http://localhost:8080/register
//http://localhost:8080/login
//http://localhost:8080 defaultnya adalah index.php

//require_once __DIR__ ."/../src/View".$path.".php";//lalu mencoba mengakses file dari pathnya

//PATH_INFO
//if (isset($_SERVER["PATH_INFO"])){
//    echo $_SERVER["PATH_INFO"];
//}else{
//    echo "TIDAK ADA PATH INFO";
//}
//http://localhost:8080/index.php/dsaljldas/asdasd
//http://localhost:8080/dsaljldas/asdasd //jika menggunakan php server bisa langsung mengakses tanpa index.php
//
//echo "<br>Hello php mvc <br>";
//echo "folder public ini yang akan di ekspos";
//harus php -S localhost:8080 di folder public

//local domain
// ubah file hosts di C:\Windows\System32\drivers\etc
// 127.0.0.1       {bebas}
//lalu jalankan di terminal php -S 127.0.0.1:8080
//bisa mengakses http://php-mvc-piter:8080/about


//Apache HTTPD
//setelah membuat local domain
//pergi ke C:\xampp\apache\conf
//buka file httpd.conf
//ubah bagian Include conf/extra/httpd-vhosts.conf (diuncoment)
//setelah itu buka file extra/httpd-vhosts.conf
//tambahkan

//<VirtualHost *:80>
//ServerAdmin admin@php-mvc.piter.com
//    DocumentRoot "C:/xampp/htdocs/PhpMVC/public"
//    ServerName php-mvc.piter
//    ErrorLog "logs/php-mvc-piter.com-error.log"
//    CustomLog "logs/php-mvc-piter.com-access.log" common
//</VirtualHost>

//pindahkan file project ke htdocs

//tambahkan file di public .htaccess
//https://github.com/codeigniter4/CodeIgniter4/blob/v4.1.3/public/.htaccess

//sekarang bisa langsung meng akses http://php-mvc-piter/