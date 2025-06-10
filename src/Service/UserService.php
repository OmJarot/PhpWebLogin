<?php

namespace Php\PhpWebLogin\Service;

use Php\PhpWebLogin\Model\UserLoginRequest;
use Php\PhpWebLogin\Model\UserLoginResponse;
use Php\PhpWebLogin\Model\UserPasswordUpdateRequest;
use Php\PhpWebLogin\Model\UserPasswordUpdateResponse;
use Php\PhpWebLogin\Model\UserProfileUpdateRequest;
use Php\PhpWebLogin\Model\UserProfileUpdateResponse;
use Php\PhpWebLogin\Model\UserRegisterRequest;
use Php\PhpWebLogin\Model\UserRegisterResponse;

interface UserService {

    function register(UserRegisterRequest $request): UserRegisterResponse;

    function login(UserLoginRequest $request): UserLoginResponse;

    function updateProfile(UserProfileUpdateRequest $request): UserProfileUpdateResponse;

    function updatePassword(UserPasswordUpdateRequest $request): UserPasswordUpdateResponse;
}