<?php

namespace Php\PhpWebLogin\Repository;

use Php\PhpWebLogin\Domain\User;

interface UserRepository {

    function save(User $user): User;

    function findById(string $id): ?User;

    function deleteAll(): void;

    function update(User $user): User;

}