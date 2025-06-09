<?php

namespace Php\PhpWebLogin\Repository;

use Php\PhpWebLogin\Config\Database;
use Php\PhpWebLogin\Domain\Session;
use Php\PhpWebLogin\Domain\User;
use PHPUnit\Framework\TestCase;

class SessionRepositoryTest extends TestCase {

    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void {
        $this->sessionRepository = new SessionRepositoryImpl(Database::getConnection());
        $this->userRepository = new UserRepositoryImpl(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->id = "piter";
        $user->name = "Piter";
        $user->password = password_hash("rahasia", PASSWORD_BCRYPT);

        $this->userRepository->save($user);
    }

    public function testSaveSuccess(): void {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = "piter";

        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findSessionById($session->id);
        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->userId, $result->userId);
    }

    public function testDeleteByIdSuccess(): void {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = "piter";

        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findSessionById($session->id);
        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->userId, $result->userId);

        $this->sessionRepository->deleteById($result->id);

        $result = $this->sessionRepository->findSessionById($session->id);
        self::assertNull($result);
    }

    public function testFindByIdNotFound(): void {
        $result = $this->sessionRepository->findSessionById("salah");
        self::assertNull($result);
    }


}
