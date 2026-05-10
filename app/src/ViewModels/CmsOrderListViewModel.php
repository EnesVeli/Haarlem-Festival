<?php

namespace App\ViewModels;

class CmsOrderListViewModel {
    public ?array $orders;

    public int $total_page_number;
    public int $current_page;
    public string $sorting;
    public int $sorting_order;
}