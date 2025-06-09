<?php

namespace Php\PhpWebLogin\Middleware;

use Php\PhpWebLogin\Middleware\Middleware;

class AuthMiddleware implements Middleware {

    function before(): void {
        session_start();
        if (!isset($_SESSION['user'])){//jika belum login akan redirect
            header('Location: /login');
            exit();
        }
    }
}