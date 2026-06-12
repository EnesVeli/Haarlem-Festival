<?php
namespace App\ViewModels;

class TicketsCategoryBaseViewModel
{
    public string $categoryKey;
    public string $contentTitle;
    public string $eventLink;
    public string $emptyMessage;
    public string $pageTitle;
    public string $pageCSS = 'tickets.css';
    public ?int $current_page = null;
    public ?int $total_page_number = null;

    public ?int $page_offset_left;
    public ?int $page_offset_right; 

    public function __construct(string $categoryKey, string $title, string $event_link, string $empty_message, ?int $current_page, ?int $total_page_number)
    {
        $this->categoryKey = $categoryKey;
        $this->contentTitle = $title;
        $this->eventLink = $event_link;
        $this->emptyMessage = $empty_message;
        $this->pageTitle = $this->contentTitle . ' - The Festival Haarlem';

        $this->current_page = $current_page;
        $this->total_page_number = $total_page_number;

        $this->calcPageOffset();
    }

    protected function calcPageOffset(){
        $offset_left = $this->current_page - 3;
        if($offset_left < 1) $offset_left = 1;

        $offset_right = $this->current_page + 3;
        if($offset_right > $this->total_page_number) $offset_right = $this->total_page_number;

        $this->page_offset_left = $offset_left;
        $this->page_offset_right = $offset_right;
    }
}
