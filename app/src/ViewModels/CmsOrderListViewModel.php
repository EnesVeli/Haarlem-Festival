<?php

namespace App\ViewModels;

class CmsOrderListViewModel {
    public ?array $orders;

    public int $sorting;
    public int $sorting_order;

    public ?int $current_page;
    public ?int $total_page_number;

    public ?int $page_offset_left;
    public ?int $page_offset_right; 

    public function calcOffsets() : void {
        $offset_left = $this->current_page - 3;
        if($offset_left < 1) $offset_left = 1;

        $offset_right = $this->current_page + 3;
        if($offset_right > $this->total_page_number) $offset_right = $this->total_page_number;

        $this->page_offset_left = $offset_left;
        $this->page_offset_right = $offset_right;
    }

    public function getUrlForPagination(int $page) : string {
        return '/cms/order?sort=' . $this->sorting . '&order=' . $this->sorting_order . '&page=' . $page;
    }
}