<?php

namespace Php\PhpWebLogin\Controller;

use Php\PhpWebLogin\App\View;
use Php\PhpWebLogin\Config\Database;
use Php\PhpWebLogin\Exception\ValidationException;
use Php\PhpWebLogin\Model\UserLoginRequest;
use Php\PhpWebLogin\Model\UserPasswordUpdateRequest;
use Php\PhpWebLogin\Model\UserProfileUpdateRequest;
use Php\PhpWebLogin\Model\UserRegisterRequest;
use Php\PhpWebLogin\Repository\SessionRepositoryImpl;
use Php\PhpWebLogin\Repository\UserRepositoryImpl;
use Php\PhpWebLogin\Service\SessionService;
use Php\PhpWebLogin\Service\SessionServiceImpl;
use Php\PhpWebLogin\Service\UserService;
use Php\PhpWebLogin\Service\UserServiceImpl;

class UserController {

    private UserService $userService;

    private SessionService $sessionService;

    public function __construct() {
        $connection = Database::getConnection();
        $userRepository = new UserRepositoryImpl($connection);
        $this->userService = new UserServiceImpl($userRepository);

        $sessionRepository = new SessionRepositoryImpl(Database::getConnection());
        $this->sessionService = new SessionServiceImpl($sessionRepository, $userRepository);
    }

    public function getRegister(): void {
        View::render("User/register", [
            "title" => "Register new User",
        ]);
    }

    public function postRegister(): void {
        $request = new UserRegisterRequest();
        $request->id = $_POST['id'];
        $request->name = $_POST['name'];
        $request->password = $_POST['password'];
        try {
            $this->userService->register($request);
            //redirect to /users/login
            View::redirect("/users/login");
        }catch (ValidationException $exception){
            View::render("User/register", [
                "title" => "Register new User",
                "error" => $exception->getMessage()
            ]);
        }
    }

    public function getLogin(): void{
        View::render("User/login", ["title" => "Login user"]);
    }
    
    public function postLogin(): void {
        $request = new UserLoginRequest();
        $request->id = $_POST['id'];
        $request->password = $_POST['password'];

        try {
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->id);

            View::redirect("/");
        }catch (ValidationException $exception){
            View::render("User/login", [
                "title" => "Login user",
                "error" => $exception->getMessage()
            ]);
        }
    }

    function getUpdateProfile(): void {
        $user = $this->sessionService->current();
        View::render("User/profile", [
            "title" => "Update profile",
            "user" => [
                "id" => $user->id,
                "name" => $user->name
            ]
        ]);
    }

    function postUpdateProfile(): void {
        $user = $this->sessionService->current();

        $request = new UserProfileUpdateRequest();
        $request->id = $user->id;
        $request->name = trim($_POST['name']);

        try {
            $this->userService->updateProfile($request);
            View::redirect("/");
        }catch (\Exception $exception){
            View::render("User/profile", [
                "title" => "Update profile",
                "error" => $exception->getMessage(),
                "user" => [
                    "id" => $user->id,
                    "name" => $_POST['name']
                ]
            ]);
        }

    }

    function getUpdatePassword(): void{
        $user = $this->sessionService->current();
        View::render("User/password", [
            "title" => "Update password",
            "user" => [
                "id" => $user->id
            ]
        ]);
    }

    function postUpdatePassword(): void {
        $user = $this->sessionService->current();
        $request = new UserPasswordUpdateRequest();
        $request->id = $user->id;
        $request->oldPassword = $_POST["oldPassword"];
        $request->newPassword = $_POST["newPassword"];

        try {
            $this->userService->updatePassword($request);
            View::redirect("/");
        }catch (ValidationException $exception){
            View::render("User/password", [
                "title" => "Update password",
                "error" => $exception->getMessage(),
                "user" => [
                    "id" => $user->id
                ]
            ]);
        }
    }

    function logout(): void{
        $this->sessionService->destroy();
        View::redirect("/");
    }
}