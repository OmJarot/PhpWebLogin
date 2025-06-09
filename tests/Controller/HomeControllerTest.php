<?php

namespace Php\PhpWebLogin\Controller;

use Php\PhpWebLogin\Config\Database;
use Php\PhpWebLogin\Domain\Session;
use Php\PhpWebLogin\Domain\User;
use Php\PhpWebLogin\Repository\SessionRepository;
use Php\PhpWebLogin\Repository\SessionRepositoryImpl;
use Php\PhpWebLogin\Repository\UserRepository;
use Php\PhpWebLogin\Repository\UserRepositoryImpl;
use Php\PhpWebLogin\Service\SessionServiceImpl;
use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase {

    private HomeController $homeController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void {
        $this->homeController = new HomeController();
        $this->userRepository = new UserRepositoryImpl(Database::getConnection());
        $this->sessionRepository = new SessionRepositoryImpl(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testGuest(): void {
        $this->homeController->index();

        $this->expectOutputRegex("[Login Management]");
    }

    public function testUserLogin(): void {
        $user = new User();
        $user->id = "piter";
        $user->name = "Piter";
        $user->password = password_hash("rahasia", PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->userId = $user->id;

        $this->sessionRepository->save($session);

        $_COOKIE[SessionServiceImpl::$COOKIE_NAME] = $session->id;

        $this->homeController->index();

        $this->expectOutputRegex("[Hello Piter]");
    }


}
