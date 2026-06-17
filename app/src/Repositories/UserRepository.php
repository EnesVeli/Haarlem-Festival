<?php
namespace App\Repositories;

use App\Enums\UserRole;
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
     * @return ?User returns user object if found, null if not.
     */
    public function findByEmail(string $email) : ?User
    {
        $stmt = $this->connection->prepare("SELECT `user_id`, `email`, `password`, `name`, `role` AS `role_`, `profile_picture_url`, `registered_at` AS `registered_at_`, `active` FROM `User` WHERE email = ? LIMIT 1;");
        $stmt->bindValue(1, $email, PDO::PARAM_STR);
        
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);

        $res = $stmt->fetch();

        return $res == false ? null : $res;
    }

    /**
     * @param int $user_id id of searched user.
     * @return ?User returns user object if found, null if not.
     */
    public function findById(int $user_id): ?User
    {
        $stmt = $this->connection->prepare("SELECT `user_id`, `email`, `password`, `name`, `role` AS `role_`, `profile_picture_url`, `registered_at` AS `registered_at_`, `active` FROM `User` WHERE `user_id` = :user_id LIMIT 1;");
        $stmt->execute(['user_id' => $user_id]);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);

        $res = $stmt->fetch();

        return $res == false ? null : $res;
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
        $stmt->bindValue(4, $user->role->value, PDO::PARAM_STR);
        $stmt->bindValue(5, $user->profile_picture_url, PDO::PARAM_INT);
        $stmt->bindValue(6, $user->active, PDO::PARAM_INT);

        $res = $stmt->execute();

        if($res === false) return false;

        return (int) $this->connection->lastInsertId();
    }

    public function updateName(int $userId, string $name) : bool
    {
        $stmt = $this->connection->prepare("UPDATE `User` SET name = :name WHERE user_id = :id");
        return $stmt->execute(['name' => $name, 'id' => $userId]);
    }

    public function updateEmail(int $userId, string $email) : bool
    {
        $stmt = $this->connection->prepare("UPDATE `User` SET email = :email WHERE user_id = :id");
        return $stmt->execute(['email' => $email, 'id' => $userId]);
    }

    public function updatePassword(int $userId, string $hash) : bool
    {
        $stmt = $this->connection->prepare("UPDATE `User` SET password = :p WHERE user_id = :id");
        return $stmt->execute(['p' => $hash, 'id' => $userId]);
    }

    public function updateProfilePictureUrl(int $userId, string $url) : bool
    {
        $stmt = $this->connection->prepare("UPDATE `User` SET profile_picture_url = :u WHERE user_id = :id");
        return $stmt->execute(['u' => $url, 'id' => $userId]);
    }

    public function updateActive(int $userId, bool $active) : bool
    {
        $stmt = $this->connection->prepare("UPDATE `User` SET active = ? WHERE user_id = ?");

        $stmt->bindValue(1, (int)$active, PDO::PARAM_INT);
        $stmt->bindValue(2, $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }

     public function updateRole(int $userId, UserRole $role) : bool
    {
        $stmt = $this->connection->prepare("UPDATE `User` SET role = :role WHERE user_id = :id");
        return $stmt->execute(['role' => $role->value, 'id' => $userId]);
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

    public function getUserListCms(int $sort, int $order, int $page, int $per_page) : array|null {
        $limit = $per_page;
        $offset = $limit * $page;

        $sorting = $this->getSortFieldCMS($sort, $order === 0 ? 'ASC' : 'DESC');

        $sql = "SELECT `user_id`, `email`, `password`, `name`, `role` AS `role_`, `profile_picture_url`, `registered_at` AS `registered_at_`, `active`
        FROM `User`
        $sorting
        LIMIT $limit OFFSET $offset;";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);

        $res = $stmt->fetchAll();

        return $res == false ? null : $res;
    }  

    private function getSortFieldCMS(int $sort, string $order){
        switch($sort){
            case 0:
                return 'ORDER BY `name` ' . $order;
            case 1:
                return 'ORDER BY `email` ' . $order;
            case 2:
                return 'ORDER BY `role` ' . $order . ', `registered_at` ' . $order;
            case 3:
                return 'ORDER BY `registered_at` ' . $order;    
            case 4:
                return 'ORDER BY `active` ' . $order . ', `registered_at` ' . $order;
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