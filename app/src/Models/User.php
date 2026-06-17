<?php

namespace App\Models;

use App\Enums\UserRole;
use DateTime;

class User
{
    public int $user_id;
    public string $email;
    public string $password;
    public string $name;
    public UserRole $role;
    public ?string $profile_picture_url = null;
    public DateTime $registered_at;
    public bool $active;

    function __set($name, $value) {
        if($name == "registered_at_"){
            $this->registation_date = new DateTime($value);
        }
        else if($name == 'role_'){
            $this->role = UserRole::from($value);
        }
    }
}