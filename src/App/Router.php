<?php

namespace Php\PhpWebLogin\App;

use Php\PhpWebLogin\Controller\HomeController;
use Php\PhpWebLogin\Controller\ProductController;


class Router {

    private static array $routes = [];

    public static function add(string $method, string $path, string $controller, string $function, array $middleware = []): void {//untuk registrasi
        self::$routes[] = [
            "method" => $method,
            "path" => $path,
            "controller" => $controller,
            "function" => $function,
            "middleware" => $middleware
        ];
    }

    public static function run(): void {

        $path = "/";
        if (isset($_SERVER["PATH_INFO"])){
            $path = $_SERVER["PATH_INFO"];//mengambil pathnya
        }

        $method = $_SERVER["REQUEST_METHOD"];//mengambil methodnya

        foreach (self::$routes as $route){//cek ke route
//            if ($path == $route['path'] && $method == $route['method']){//jika path dan methodnya sama maka atau ada
            $pattern = "#^".$route['path']."$#";
            if (preg_match($pattern, $path, $variables)&& $method == $route['method']){//regex akan di simpan di variables

                foreach ($route['middleware'] as $middleware){
                    $instance = new $middleware;
                    $instance->before();
                }

                $function = $route['function'];//mengambil functionnya

                $controller = new $route['controller'];//membuat object dari controller (new HomeController::class)
//                $controller->$function();//memanggil function menggunakan string

                array_shift($variables);//menghapus data pertama
                call_user_func_array([$controller, $function], $variables);//memanggil function, variables akan dikirim sebagai parameter
                return;
            }
        }
        //jika tidak ketemu akan 404
        http_response_code(404);
        echo "NOT FOUND";
    }
}