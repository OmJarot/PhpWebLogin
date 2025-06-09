<?php
namespace Php\PhpWebLogin\App{

    function header(string $value): void {//agar bisa lihat redirect
        echo $value;
    }
}

namespace Php\PhpWebLogin\Service{

    function setcookie(string $name, string $value, int $time , string $path): void {
        echo "$name: $value";
    }
}

namespace Php\PhpWebLogin\Controller {

    use Php\PhpWebLogin\Config\Database;
    use Php\PhpWebLogin\Domain\User;
    use Php\PhpWebLogin\Repository\SessionRepository;
    use Php\PhpWebLogin\Repository\SessionRepositoryImpl;
    use Php\PhpWebLogin\Repository\UserRepository;
    use Php\PhpWebLogin\Repository\UserRepositoryImpl;
    use PHPUnit\Framework\TestCase;

    class UserControllerTest extends TestCase {

        private UserController $controller;
        private UserRepository $userRepository;
        private SessionRepository $sessionRepository;

        protected function setUp(): void {
            $this->controller = new UserController();

            $this->sessionRepository = new SessionRepositoryImpl(Database::getConnection());
            $this->sessionRepository->deleteAll();

            $this->userRepository = new UserRepositoryImpl(Database::getConnection());

            $this->userRepository->deleteAll();
        }


        public function testGetRegister(): void {

            $this->controller->getRegister();

            $this->expectOutputRegex("[Register]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Name]");
            $this->expectOutputRegex("[Register new User]");
        }

        public function testPostRegisterSuccess(): void {
            $_POST['id'] = "piter";
            $_POST['name'] = "Piter";
            $_POST['password'] = "rahasia";

            putenv("mode=test");
            $this->controller->postRegister();

            $this->expectOutputRegex("[Location: /users/login]");
        }


        public function testPostRegisterValidationError(): void {
            $_POST['id'] = "";
            $_POST['name'] = "";
            $_POST['password'] = "";

            $this->controller->postRegister();

            $this->expectOutputRegex("[Register]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Name]");
            $this->expectOutputRegex("[Register new User]");
            $this->expectOutputRegex("[Id, Name, Password can not blank]");
        }

        public function testRegisterDuplicateError(): void {
            $user = new User();
            $user->id = "piter";
            $user->name = "Piter";
            $user->password = "rahasia";

            $this->userRepository->save($user);

            $_POST['id'] = "piter";
            $_POST['name'] = "Piter";
            $_POST['password'] = "rahasia";

            $this->controller->postRegister();

            $this->expectOutputRegex("[Register]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Name]");
            $this->expectOutputRegex("[Register new User]");
            $this->expectOutputRegex("[User Id already exists]");
        }

        public function testGetLogin(): void {
            $this->controller->getLogin();

            $this->expectOutputRegex("[Login user]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Password]");
        }

        public function testPostLoginSuccess(): void {
            $user = new User();
            $user->id = "piter";
            $user->name = "Piter";
            $user->password = password_hash("rahasia", PASSWORD_BCRYPT);

            $this->userRepository->save($user);

            $_POST['id'] = "piter";
            $_POST['password'] = "rahasia";

            putenv("mode=test");
            $this->controller->postLogin();

            $this->expectOutputRegex("[X-PTR-SESSION: ]");
            $this->expectOutputRegex("[Location: /]");
        }

        public function testLoginValidationError(): void {
            $_POST['id'] = "";
            $_POST['password'] = "";

            $this->controller->postLogin();

            $this->expectOutputRegex("[Login user]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Id, Password can not blank]");
        }

        public function testPostLoginUserNotFound(): void {
            $_POST['id'] = "notfound";
            $_POST['password'] = "notfound";

            $this->controller->postLogin();

            $this->expectOutputRegex("[Login user]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Id or Password is wrong]");
        }

        public function testPostLoginWrongPassword(): void {
            $user = new User();
            $user->id = "piter";
            $user->name = "Piter";
            $user->password = password_hash("rahasia", PASSWORD_BCRYPT);

            $this->userRepository->save($user);

            $_POST['id'] = "piter";
            $_POST['password'] = "salah";

            $this->controller->postLogin();

            $this->expectOutputRegex("[Login user]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Id or Password is wrong]");
        }


    }
}
