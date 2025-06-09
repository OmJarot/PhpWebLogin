<?php

namespace Php\PhpWebLogin\Repository;

use Php\PhpWebLogin\Domain\Session;

interface SessionRepository {

    function save(Session $session): Session;

    function findSessionById(string $id): ?Session;

    function deleteById(string $id): void;

    function deleteAll():void;
}