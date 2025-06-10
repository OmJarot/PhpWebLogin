<?php

namespace Php\PhpWebLogin\Model;

class UserPasswordUpdateRequest {
    public ?string $id = null;
    public ?string $oldPassword = null;
    public ?string $newPassword = null;
}