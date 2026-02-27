<?php
namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class PasswordResetTokenRepository extends Repository
{
    public function getTokenByUserId(string $user_id): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM `PasswordResetToken` WHERE `user_id` = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        $token = $stmt->fetch(PDO::FETCH_ASSOC);

        return $token ?: null;
    }

    public function getTokenByKey(string $key): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM `PasswordResetToken` WHERE `key` = :key");
        $stmt->execute(['key' => $key]);
        $token = $stmt->fetch(PDO::FETCH_ASSOC);

        return $token ?: null;
    }

    public function createNewToken(string $user_id, string $key) : bool
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO `PasswordResetToken` (`user_id`, `key`, `created_at`) VALUES (:user_id, :key, NOW())"
        );

        return $stmt->execute([
            'user_id' => $user_id,
            'key'     => $key
        ]);
    }

    public function updateToken(int $token_id, string $key){
        $stmt = $this->connection->prepare(
            "UPDATE `PasswordResetToken` SET `key` = :key, `created_at` = NOW() WHERE `token_id` = :token_id"
        );

        return $stmt->execute([
            'token_id' => $token_id,
            'key'     => $key
        ]);
    }

    public function deleteToken(int $token_id) : bool
    {
        $stmt = $this->connection->prepare(
            "DELETE FROM `PasswordResetToken` WHERE `token_id` = :token_id"
        );

        return $stmt->execute([
            'token_id' => $token_id
        ]);
    }
}