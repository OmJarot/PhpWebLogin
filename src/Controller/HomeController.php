<?php

namespace Php\PhpWebLogin\Controller;

use Php\PhpWebLogin\App\View;
use Php\PhpWebLogin\Config\Database;
use Php\PhpWebLogin\Repository\SessionRepositoryImpl;
use Php\PhpWebLogin\Repository\UserRepositoryImpl;
use Php\PhpWebLogin\Service\SessionService;
use Php\PhpWebLogin\Service\SessionServiceImpl;

class HomeController {

    private SessionService $sessionService;

    public function __construct() {
        $sessionRepository = new SessionRepositoryImpl(Database::getConnection());
        $userRepository = new UserRepositoryImpl(Database::getConnection());
        $this->sessionService = new SessionServiceImpl($sessionRepository, $userRepository);
    }

    function index(): void{
        $user = $this->sessionService->current();
        if ($user == null){
            View::render("Home/index", [
                "title" => "PHP Login Management"
            ]);
        }else{
            View::render("Home/dashboard", [
                "title" => "Dashboard",
                "user" => [
                    "name" => $user->name
                ]
            ]);
        }

    }
}