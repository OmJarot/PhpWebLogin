<?php

namespace Php\PhpWebLogin\App{
    function header(string $value): void {//agar bisa lihat redirect
        echo $value;
    }
}
namespace Php\PhpWebLogin\Middleware {

use Php\PhpWebLogin\Config\Database;
use Php\PhpWebLogin\Domain\Session;
use Php\PhpWebLogin\Domain\User;
use Php\PhpWebLogin\Repository\SessionRepository;
use Php\PhpWebLogin\Repository\SessionRepositoryImpl;
use Php\PhpWebLogin\Repository\UserRepository;
use Php\PhpWebLogin\Repository\UserRepositoryImpl;
use Php\PhpWebLogin\Service\SessionServiceImpl;
use PHPUnit\Framework\TestCase;

class MustNotLoginMiddlewareTest extends TestCase {

    private MustNotLoginMiddleware $middleware;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void {
        $this->middleware = new MustNotLoginMiddleware();
        $this->userRepository = new UserRepositoryImpl(Database::getConnection());
        $this->sessionRepository = new SessionRepositoryImpl(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testBeforeGuest(): void {
        putenv("mode=test");
        $this->middleware->before();

        $this->expectOutputRegex("[]");
    }

    public function testBeforeUser(): void {
        $user = new User();
        $user->id = "piter";
        $user->name = "Piter";
        $user->password = password_hash("rahasia", PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->userId = "piter";

        $this->sessionRepository->save($session);

        $_COOKIE[SessionServiceImpl::$COOKIE_NAME] = $session->id;

        putenv("mode=test");
        $this->middleware->before();

        $this->expectOutputRegex("[Location: /]");

    }


}
}
