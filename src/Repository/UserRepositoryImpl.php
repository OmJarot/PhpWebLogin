<?php

namespace Php\PhpWebLogin\Repository;

use Php\PhpWebLogin\Domain\User;

class UserRepositoryImpl implements UserRepository {

    private \PDO $connection;

    public function __construct(\PDO $connection) {
        $this->connection = $connection;
    }

    public function save(User $user): User {
        $statement = $this->connection->prepare("Insert into users(id, name, password) values (?,?,?)");
        $statement->execute([$user->id, $user->name, $user->password]);
        return $user;
    }

    public function findById(string $id): ?User {
        $statement = $this->connection->prepare("Select id, name, password from users where id = ?");
        $statement->execute([$id]);

        if ($row = $statement->fetch()){
            $user = new User();
            $user->id = $row['id'];
            $user->name = $row['name'];
            $user->password = $row['password'];
            return $user;
        }else{
            return null;
        }
    }

    public function deleteAll(): void {
        $this->connection->exec("Delete from users");
    }
}