<?php

namespace Php\PhpWebLogin\Controller;

use Php\PhpWebLogin\App\View;
use Php\PhpWebLogin\Config\Database;
use Php\PhpWebLogin\Exception\ValidationException;
use Php\PhpWebLogin\Model\UserRegisterRequest;
use Php\PhpWebLogin\Repository\UserRepositoryImpl;
use Php\PhpWebLogin\Service\UserService;
use Php\PhpWebLogin\Service\UserServiceImpl;

class RegisterController {

    private UserService $userService;

    public function __construct() {
        $connection = Database::getConnection();
        $userRepository = new UserRepositoryImpl($connection);
        $this->userService = new UserServiceImpl($userRepository);
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
}