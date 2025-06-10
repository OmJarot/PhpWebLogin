<?php

namespace Php\PhpWebLogin\Middleware;

use Php\PhpWebLogin\App\View;
use Php\PhpWebLogin\Config\Database;
use Php\PhpWebLogin\Repository\SessionRepositoryImpl;
use Php\PhpWebLogin\Repository\UserRepositoryImpl;
use Php\PhpWebLogin\Service\SessionService;
use Php\PhpWebLogin\Service\SessionServiceImpl;

class MustLoginMiddleware implements Middleware {

    private SessionService $sessionService;

    public function __construct() {
        $userRepository = new UserRepositoryImpl(Database::getConnection());
        $sessionRepository = new SessionRepositoryImpl(Database::getConnection());
        $this->sessionService = new SessionServiceImpl($sessionRepository, $userRepository);
    }

    function before(): void {
        $session = $this->sessionService->current();
        if ($session == null){
            View::redirect("/users/login");
        }
    }
}