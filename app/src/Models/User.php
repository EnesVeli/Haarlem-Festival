<?php

namespace App\Models;

use App\Enums\UserRole;
use DateTime;

class User
{
    public int $user_id;
    public string $email;
    public ?string $password = null;
    public string $name;
    public UserRole $role;
    public ?string $profile_picture_url = null;
    public ?DateTime $registered_at = null;
}