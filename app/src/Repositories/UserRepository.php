<?php
namespace App\Repositories;

use App\Framework\Repository;
use App\Models\User;
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

    /**
     * @param string $email of searched user.
     * @return User|null|bool returns user object if found, null if not. False if there were errors during query execution.
     */
    public function findByEmail(string $email): User|null|bool
    {
        $stmt = $this->connection->prepare("SELECT `user_id`, `email`, `password`, `name`, `role` AS `role_`, `profile_picture_url`, `registered_at` AS `registered_at_`, `active` FROM `User` WHERE `email` = :email LIMIT 1;");
        $stmt->execute(['email' => $email]);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);

        return $stmt->fetch();
    }

    /**
     * @param int $user_id id of searched user.
     * @return User|null|bool returns user object if found, null if not. False if there were errors during query execution.
     */
    public function findById(int $user_id): User|null|bool
    {
        $stmt = $this->connection->prepare("SELECT `user_id`, `email`, `password`, `name`, `role` AS `role_`, `profile_picture_url`, `registered_at` AS `registered_at_`, `active` FROM `User` WHERE `user_id` = :user_id LIMIT 1;");
        $stmt->execute(['user_id' => $user_id]);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);

        return $stmt->fetch();
    }

    /**
     * @param User $user user to be created (user-id and registered_at fields are ignored).
     * @return int|bool On succes returns id of created user, on failure returns false.
     */
    public function create(User $user): int|bool
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO `User`(`email`, `password`, `name`, `role`, `profile_picture_url`, `registered_at`, `active`) VALUES 
            (?, ?, ?, ?, ?, NOW(), ?);"
        );

        $stmt->bindValue(1, $user->email, PDO::PARAM_STR);
        $stmt->bindValue(2, $user->password, PDO::PARAM_STR);
        $stmt->bindValue(3, $user->name, PDO::PARAM_STR);
        $stmt->bindValue(4, $user->role, PDO::PARAM_STR);
        $stmt->bindValue(5, $user->profile_picture_url, PDO::PARAM_INT);
        $stmt->bindValue(6, $user->active, PDO::PARAM_INT);

        $res = $stmt->execute();

        if($res === false) return false;

        return (int) $this->connection->lastInsertId();
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

    public function getUserListCms(int $sort, int $order, int $page, int $per_page) : array|null|bool {
        $limit = $per_page;
        $offset = $limit * $page;

        $sorting = $this->getSortFieldCMS($sort, $order === 0 ? 'ASC' : 'DESC');

        $sql = "SELECT `user_id`, `email`, `password`, `name`, `role`, `profile_picture_url`, `registered_at`
        FROM `User`
        $sorting
        LIMIT $limit OFFSET $offset;";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);

        return $stmt->fetchAll();
    }  

    private function getSortFieldCMS(int $sort, string $order){
        switch($sort){
            case 0:
                return 'ORDER BY `registered_at` ' . $order;
            case 1:
                return 'ORDER BY `role` ' . $order . ', `registered_at` ' . $order;
            case 2:
                return 'ORDER BY `name` ' . $order;
            case 3:
                return 'ORDER BY `email` ' . $order;
        }

        return '';
    }

    public function countAllUsers() : int|false {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM `User`;");

        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_DEFAULT);

        return $res === false ? false : $res[0];
    }
}