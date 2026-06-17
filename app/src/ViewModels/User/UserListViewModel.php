<?php

namespace App\ViewModels\User;

class UserListViewModel {
    public UserTopper $topper;

    public array $users;

    public int $cur_page;
    public int $total_page_number;
    public int $page_offset;
    public int $page_limit;

    public int $sorting;
    public int $sort_order;
}