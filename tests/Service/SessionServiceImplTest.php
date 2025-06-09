<?php

namespace Php\PhpWebLogin\Service;

use Php\PhpWebLogin\Config\Database;
use Php\PhpWebLogin\Domain\Session;
use Php\PhpWebLogin\Domain\User;
use Php\PhpWebLogin\Repository\SessionRepository;
use Php\PhpWebLogin\Repository\SessionRepositoryImpl;
use Php\PhpWebLogin\Repository\UserRepository;
use Php\PhpWebLogin\Repository\UserRepositoryImpl;
use PHPUnit\Framework\TestCase;

function setcookie(string $name, string $value) {
    echo "$name: $value";
}

class SessionServiceImplTest extends TestCase {

    private SessionService $sessionService;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void {
        $this->sessionRepository = new SessionRepositoryImpl(Database::getConnection());
        $this->userRepository = new UserRepositoryImpl(Database::getConnection());
        $this->sessionService = new SessionServiceImpl($this->sessionRepository, $this->userRepository);

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->id = "piter";
        $user->name = "Piter";
        $user->password = password_hash("rahasia", PASSWORD_BCRYPT);

        $this->userRepository->save($user);
    }

    public function testCreateSession(): void {
        $session = $this->sessionService->create("piter");

        $this->expectOutputRegex("[X-PTR-COOKIE: $session->id]");

        $result = $this->sessionRepository->findSessionById($session->id);

        self::assertEquals("piter", $result->userId);
    }

    public function testDestroy(): void {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = "piter";

        $this->sessionRepository->save($session);

        $_COOKIE[SessionServiceImpl::$COOKIE_NAME] = $session->id;

        $this->sessionService->destroy();

        $this->expectOutputRegex("[X-PTR-COOKIE: ]");

        $result = $this->sessionRepository->findSessionById($session->id);
        self::assertNull($result);
    }

    public function testCurrent(): void {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = "piter";

        $this->sessionRepository->save($session);
        $_COOKIE[SessionServiceImpl::$COOKIE_NAME] = $session->id;

        $user = $this->sessionService->current();

        self::assertEquals($session->userId, $user->id);

    }

}
