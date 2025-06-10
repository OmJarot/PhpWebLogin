<?php
namespace Php\PhpWebLogin\Controller {

    require_once __DIR__ . "/../Helper/helper.php";

    use Php\PhpWebLogin\Config\Database;
    use Php\PhpWebLogin\Domain\Session;
    use Php\PhpWebLogin\Domain\User;
    use Php\PhpWebLogin\Repository\SessionRepository;
    use Php\PhpWebLogin\Repository\SessionRepositoryImpl;
    use Php\PhpWebLogin\Repository\UserRepository;
    use Php\PhpWebLogin\Repository\UserRepositoryImpl;
    use Php\PhpWebLogin\Service\SessionServiceImpl;
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

        public function testLogout(): void {
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
            $this->controller->logout();

            $this->expectOutputRegex("[X-PTR-SESSION: ]");
            $this->expectOutputRegex("[Location: /]");
        }

        public function testGetUpdateProfile(): void {
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

            $this->controller->getUpdateProfile();

            $this->expectOutputRegex("[Profile]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[piter]");
            $this->expectOutputRegex("[Name]");
            $this->expectOutputRegex("[Piter]");
        }


        public function testPostUpdateProfile(): void {
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

            $_POST['name'] = "piter new";

            putenv("mode=test");

            $this->controller->postUpdateProfile();

            $this->expectOutputRegex("[Location: /]");

            $result = $this->userRepository->findById("piter");
            self::assertEquals("piter new", $result->name);
        }

        public function testUpdateProfileValidationError(): void {
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

            $_POST['name'] = "";

            putenv("mode=test");

            $this->controller->postUpdateProfile();

            $this->expectOutputRegex("[Profile]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[piter]");
            $this->expectOutputRegex("[Name]");
            $this->expectOutputRegex("[Id, name can not blank]");
        }

        public function testGetUpdatePassword(): void {
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

            $this->controller->getUpdateProfile();

            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[piter]");
        }
        public function testPostUpdatePassword(): void {
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

            $_POST["oldPassword"] = "rahasia";
            $_POST["newPassword"] = "piter new";
            $this->controller->postUpdatePassword();

            $this->expectOutputRegex("[Location: /]");

            $result = $this->userRepository->findById($user->id);

            self::assertTrue(password_verify("piter new", $result->password));
        }

        public function testPostUpdatePasswordValidationError(): void {
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

            $_POST["oldPassword"] = "";
            $_POST["newPassword"] = "";
            $this->controller->postUpdatePassword();

            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[piter]");
            $this->expectOutputRegex("[Id, Old password, new Password can not blank]");
        }

        public function testPostUpdatePasswordWrongOldPassword(): void {
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

            $_POST["oldPassword"] = "salah";
            $_POST["newPassword"] = "piter new";
            $this->controller->postUpdatePassword();

            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[piter]");
            $this->expectOutputRegex("[Old password is wrong]");
        }


    }
}
