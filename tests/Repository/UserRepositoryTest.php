<?php

namespace Php\PhpWebLogin\Repository;

use Php\PhpWebLogin\Config\Database;
use Php\PhpWebLogin\Domain\User;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase {

    private UserRepository $userRepository;

    protected function setUp(): void {
        $this->userRepository = new UserRepositoryImpl(Database::getConnection());

        $this->userRepository->deleteAll();
    }

    public function testSaveSuccess(): void {
        $user = new User();
        $user->id = "piter";
        $user->name = "Piter";
        $user->password = "rahasia";

        $result = $this->userRepository->save($user);

        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->name, $result->name);
        self::assertEquals($user->password, $result->password);
    }

    public function testFindByIdNotFound(): void {
        $result = $this->userRepository->findById("tidak ada");

        self::assertNull($result);
    }


}
