<?php

namespace Php\PhpWebLogin\Service;

use Php\PhpWebLogin\Config\Database;
use Php\PhpWebLogin\Domain\User;
use Php\PhpWebLogin\Exception\ValidationException;
use Php\PhpWebLogin\Model\UserLoginRequest;
use Php\PhpWebLogin\Model\UserPasswordUpdateRequest;
use Php\PhpWebLogin\Model\UserProfileUpdateRequest;
use Php\PhpWebLogin\Model\UserRegisterRequest;
use Php\PhpWebLogin\Repository\SessionRepositoryImpl;
use Php\PhpWebLogin\Repository\UserRepository;
use Php\PhpWebLogin\Repository\UserRepositoryImpl;
use PHPUnit\Framework\TestCase;

class UserServiceImplTest extends TestCase {

    private UserService $userService;
    private UserRepository $userRepository;

    protected function setUp(): void {
        $connection = Database::getConnection();

        $sessionRepository = new SessionRepositoryImpl($connection);
        $sessionRepository->deleteAll();

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

    public function testNotFound(): void {
        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->id = "piter";
        $request->password = "piter";

        $this->userService->login($request);
    }

    public function testWrongPassword(): void {
        $user = new User();
        $user->id = "piter";
        $user->name = "Piter";
        $user->password = password_hash("rahasia", PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->id = "piter";
        $request->password = "piterdsa";

        $this->userService->login($request);
    }

    public function testLoginSuccess(): void {
        $user = new User();
        $user->id = "piter";
        $user->name = "Piter";
        $user->password = password_hash("rahasia", PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $request = new UserLoginRequest();
        $request->id = "piter";
        $request->password = "rahasia";

        $result = $this->userService->login($request);

        self::assertEquals($request->id, $result->user->id);
        self::assertTrue(password_verify($request->password, $result->user->password));
    }

    public function testUpdateSuccess(): void {
        $user = new User();
        $user->id = "piter";
        $user->name = "Piter";
        $user->password = "rahasia";

        $this->userRepository->save($user);

        $request = new UserProfileUpdateRequest();
        $request->id = "piter";
        $request->name = "piter new";

        $this->userService->updateProfile($request);

        $result = $this->userRepository->findById($request->id);

        self::assertEquals($request->name, $result->name);
    }

    public function testValidationError(): void {
        $this->expectException(ValidationException::class);

        $request = new UserProfileUpdateRequest();
        $request->id = "";
        $request->name = "";

        $this->userService->updateProfile($request);
    }

    public function testUpdateNotFound(): void {
        $this->expectException(ValidationException::class);

        $request = new UserProfileUpdateRequest();
        $request->id = "piter";
        $request->name = "piter new";

        $this->userService->updateProfile($request);

        $result = $this->userRepository->findById($request->id);

        self::assertEquals($request->name, $result->name);
    }

    public function testUpdatePasswordSuccess(): void {
        $user = new User();
        $user->id = "piter";
        $user->name = "Piter";
        $user->password = password_hash("rahasia", PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $request = new UserPasswordUpdateRequest();
        $request->id = "piter";
        $request->oldPassword = "rahasia";
        $request->newPassword = "new password";

        $this->userService->updatePassword($request);

        $userFind = $this->userRepository->findById($user->id);

        self::assertTrue(password_verify($request->newPassword, $userFind->password));
    }

    public function testUpdatePasswordValidationError(): void {
        $this->expectException(ValidationException::class);

        $request = new UserPasswordUpdateRequest();
        $request->id = "piter";
        $request->oldPassword = "";
        $request->newPassword = "";

        $this->userService->updatePassword($request);

    }

    public function testUpdatePasswordWrongPassword(): void {
        $this->expectException(ValidationException::class);

        $user = new User();
        $user->id = "piter";
        $user->name = "Piter";
        $user->password = password_hash("rahasia", PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $request = new UserPasswordUpdateRequest();
        $request->id = "piter";
        $request->oldPassword = "salah";
        $request->newPassword = "new password";

        $this->userService->updatePassword($request);
    }

    public function testUpdatePasswordNotFound(): void {
        $this->expectException(ValidationException::class);

        $request = new UserPasswordUpdateRequest();
        $request->id = "piter";
        $request->oldPassword = "piter";
        $request->newPassword = "new password";

        $this->userService->updatePassword($request);
    }

}
