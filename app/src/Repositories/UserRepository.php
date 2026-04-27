<?php
namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class UserRepository extends Repository
{
    private static ?UserRepository $_instance = null;

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance() : UserRepository {
        if(self::$_instance === null) self::$_instance = new UserRepository();

        return self::$_instance;
    }

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

    public function findById(int $userId): ?array
{
    $stmt = $this->connection->prepare("SELECT * FROM `User` WHERE user_id = :id LIMIT 1");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user ?: null;
}

public function updateName(int $userId, string $name): void
{
    $stmt = $this->connection->prepare("UPDATE `User` SET name = :name WHERE user_id = :id");
    $stmt->execute(['name' => $name, 'id' => $userId]);
}

public function updateEmail(int $userId, string $email): void
{
    $stmt = $this->connection->prepare("UPDATE `User` SET email = :email WHERE user_id = :id");
    $stmt->execute(['email' => $email, 'id' => $userId]);
}

public function updatePassword(int $userId, string $hash): void
{
    $stmt = $this->connection->prepare("UPDATE `User` SET password = :p WHERE user_id = :id");
    $stmt->execute(['p' => $hash, 'id' => $userId]);
}

public function updateProfilePictureUrl(int $userId, string $url): void
{
    $stmt = $this->connection->prepare("UPDATE `User` SET profile_picture_url = :u WHERE user_id = :id");
    $stmt->execute(['u' => $url, 'id' => $userId]);
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