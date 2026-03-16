<?php

namespace App\Models;

use DateTime;

class PasswordResetToken{
    public int $token_id;
    public int $user_id;
    public string $key;
    public DateTime $creation_date;
    public ?DateTime $activation_date;

    function __set($name, $value) {
        if($name == "created_at") {
            $this->creation_date = new DateTime($value);
        }
        else if($name == "activated_at"){
            $this->activation_date = $value == null ? null : new DateTime($value);
        }
    }
}