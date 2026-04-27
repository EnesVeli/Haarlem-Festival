<?php
namespace App\Repositories;

use App\Framework\Repository;
use App\Models\PasswordResetToken;
use PDO;

class PasswordResetTokenRepository extends Repository
{
    private static ?PasswordResetTokenRepository $_instance = null;

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance() : PasswordResetTokenRepository {
        if(self::$_instance === null) self::$_instance = new PasswordResetTokenRepository();

        return self::$_instance;
    }

    public function getTokenByUserId(string $user_id): ?PasswordResetToken
    {
        $stmt = $this->connection->prepare("SELECT `token_id`, `user_id`, `key`, `created_at`, `activated_at` FROM `PasswordResetToken` WHERE `user_id` = :user_id");
        $stmt->execute(['user_id' => $user_id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, PasswordResetToken::class);
        $res = $stmt->fetch();

        return $res == false ? null : $res;
    }

    public function getTokenByKey(string $key): ?PasswordResetToken
    {
        $stmt = $this->connection->prepare("SELECT `token_id`, `user_id`, `key`, `created_at`, `activated_at` FROM `PasswordResetToken` WHERE `key` = :key");
        $stmt->execute(['key' => $key]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, PasswordResetToken::class);
        $res = $stmt->fetch();

        return $res == false ? null : $res;
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

    public function updateToken(int $token_id, string $key) : bool
    {
        $stmt = $this->connection->prepare(
            "UPDATE `PasswordResetToken` SET `key` = :key, `created_at` = NOW(), `activated_at` = NULL WHERE `token_id` = :token_id"
        );

        return $stmt->execute([
            'token_id' => $token_id,
            'key'     => $key
        ]);
    }

    public function setActivationTimeAsNow(int $token_id){
        $stmt = $this->connection->prepare(
            "UPDATE `PasswordResetToken` SET `activated_at` = NOW() WHERE `token_id` = :token_id"
        );

        return $stmt->execute(['token_id' => $token_id]);
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