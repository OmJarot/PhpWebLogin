<?php

namespace Php\PhpWebLogin\Controller;

use Php\PhpWebLogin\App\View;

class HomeController {

    function index(): void {
        $model = [//model, bisa dalam bentuk array atau object
            "title" => "Belajar PHP MVC",
            "content" => "Selamat Belajar PHP MVC"
        ];
//        require __DIR__ . "/../View/Home/index.php";
        //atau
        View::render("Home/index", $model);//render view
    }

    function hello(): void{
        echo "HomeController.hello()";
    }

    function world(): void{
        echo "HomeController.world()";
    }

    function about(): void {
        echo "Author: Piter Pangaribuan";
    }

    function login() {
        //model
        $request = [
            "username" => "piter",
            "password" => "rahasia"
        ];


        $request = [
            "message" => "Login success",
        ];
        //kirim ke view
    }
}