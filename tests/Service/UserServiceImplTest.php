<?php

namespace Php\PhpWebLogin\Service;

use Php\PhpWebLogin\Config\Database;
use Php\PhpWebLogin\Domain\User;
use Php\PhpWebLogin\Exception\ValidationException;
use Php\PhpWebLogin\Model\UserRegisterRequest;
use Php\PhpWebLogin\Repository\UserRepository;
use Php\PhpWebLogin\Repository\UserRepositoryImpl;
use PHPUnit\Framework\TestCase;

class UserServiceImplTest extends TestCase {

    private UserService $userService;
    private UserRepository $userRepository;

    protected function setUp(): void {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepositoryImpl($connection);
        $this->userService = new UserServiceImpl($this->userRepository);
        $this->userRepository->deleteAll();
    }


    public function testRegisterSuccess(): void {
        $request = new UserRegisterRequest();
        $request->id = "piter";
        $request->name = "Piter";
        $request->password = "rahasia";

        $response = $this->userService->register($request);

        self::assertEquals($request->id ,$response->user->id);
        self::assertEquals($request->name ,$response->user->name);
        self::assertNotEquals($request->password ,$response->user->password);
        self::assertTrue(password_verify($request->password, $response->user->password));
    }

    public function testRegisterFailed(): void {

        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id = "";
        $request->name = "";
        $request->password = "";

        $response = $this->userService->register($request);
    }

    public function testRegisterDuplicate(): void {
        $user = new User();
        $user->id = "piter";
        $user->name = "Piter";
        $user->password = "rahasia";

        $this->userRepository->save($user);
        $this->expectException(ValidationException::class);
        $request = new UserRegisterRequest();
        $request->id = "piter";
        $request->name = "Piter";
        $request->password = "rahasia";

        $response = $this->userService->register($request);
    }


}
