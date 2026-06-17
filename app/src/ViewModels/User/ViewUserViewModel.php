<?php

namespace App\ViewModels\User;

use App\Models\User;

class ViewUserViewModel {
    public UserTopper $topper;
    public User $user;
    public array $roles;
}