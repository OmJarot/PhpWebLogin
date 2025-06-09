<?php

namespace Php\PhpWebLogin\Service;

use Php\PhpWebLogin\Config\Database;
use Php\PhpWebLogin\Domain\User;
use Php\PhpWebLogin\Exception\ValidationException;
use Php\PhpWebLogin\Model\UserRegisterRequest;
use Php\PhpWebLogin\Model\UserRegisterResponse;
use Php\PhpWebLogin\Repository\UserRepository;

class UserServiceImpl implements UserService {
    
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }
    
    function register(UserRegisterRequest $request): UserRegisterResponse {
        $this->validationUserRegisterRequest($request);

        try {
            Database::beginTransaction();
            $user = $this->userRepository->findById($request->id);
            if ($user != null){
                throw new ValidationException("User Id already exists");
            }

            $user = new User();
            $user->id = $request->id;
            $user->name = $request->name;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);

            $this->userRepository->save($user);

            $response = new UserRegisterResponse();
            $response->user = $user;

            Database::commitTransaction();
            return $response;
        }catch (\Exception $exception){
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validationUserRegisterRequest(UserRegisterRequest $request): void {
        if ($request ->id == null || $request->name == null || $request->password == null ||
            $request->id == "" || $request->name == "" || $request->password == ""){
            throw new ValidationException("Id, Name, Password can not blank");
        }
    }
}