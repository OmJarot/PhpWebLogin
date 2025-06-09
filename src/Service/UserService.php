<?php

namespace Php\PhpWebLogin\Service;

use Php\PhpWebLogin\Model\UserRegisterRequest;
use Php\PhpWebLogin\Model\UserRegisterResponse;

interface UserService {

    function register(UserRegisterRequest $request): UserRegisterResponse;
}