<?php

namespace Php\PhpWebLogin\Controller;

use Php\PhpWebLogin\App\View;
use Php\PhpWebLogin\Config\Database;
use Php\PhpWebLogin\Exception\ValidationException;
use Php\PhpWebLogin\Model\UserLoginRequest;
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
}