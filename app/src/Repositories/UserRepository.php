<?php
namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class UserRepository extends Repository
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM `User` WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function findByUserId(string $user_id): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM `User` WHERE `user_id` = :user_id LIMIT 1");
        $stmt->execute(['user_id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function create(string $name, string $email, string $password, string $role = 'customer'): int
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO `User` (name, email, password, role, registered_at)
             VALUES (:name, :email, :password, :role, NOW())"
        );

        $stmt->execute([
            'name'     => $name,
            'email'    => $email,
            'password' => $password,
            'role'     => $role
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function changePassword(int $user_id, string $password) : bool{
        $stmt = $this->connection->prepare(
            "UPDATE `User` SET `password` = :password WHERE `user_id` = :user_id"
        );

        return $stmt->execute([
            'password' => $password,
            'user_id'  => $user_id
        ]);
    }
}