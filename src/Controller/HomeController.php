<?php

namespace Php\PhpWebLogin\Controller;

use Php\PhpWebLogin\App\View;

class HomeController {

    function index(): void{
        View::render("Home/index", [
            "title" => "PHP Login Management"
        ]);
    }
}