<?php

namespace Php\PhpWebLogin\Repository;

use Php\PhpWebLogin\Domain\Session;

class SessionRepositoryImpl implements SessionRepository {

    private \PDO $connection;

    public function __construct(\PDO $connection) {
        $this->connection = $connection;
    }

    function save(Session $session): Session {
        $statement = $this->connection->prepare("Insert into sessions(id, user_id) values (?, ?)");
        $statement->execute([$session->id, $session->userId]);
        return $session;
    }

    function findSessionById(string $id): ?Session {
        $statement = $this->connection->prepare("select id, user_id from sessions where id = ?");
        $statement->execute([$id]);

        try {
            if ($row = $statement->fetch()){
                $session = new Session();
                $session->id = $row['id'];
                $session->userId = $row['user_id'];
                return $session;
            }else{
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    function deleteById(string $id): void {
        $statement = $this->connection->prepare("delete from sessions where id = ?");
        $statement->execute([$id]);
    }

    function deleteAll(): void {
        $this->connection->exec("delete from sessions");
    }
}