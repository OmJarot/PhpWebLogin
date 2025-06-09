<?php

namespace Php\PhpWebLogin\Service;

use Php\PhpWebLogin\Model\UserLoginRequest;
use Php\PhpWebLogin\Model\UserLoginResponse;
use Php\PhpWebLogin\Model\UserRegisterRequest;
use Php\PhpWebLogin\Model\UserRegisterResponse;

interface UserService {

    function register(UserRegisterRequest $request): UserRegisterResponse;

    function login(UserLoginRequest $request): UserLoginResponse;
}