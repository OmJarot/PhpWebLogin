<?php

namespace Php\PhpWebLogin\Service;

use Php\PhpWebLogin\Domain\Session;
use Php\PhpWebLogin\Domain\User;

interface SessionService {

    function create(string $userID): Session;

    function destroy(): void;

    function current(): ?User;
}