<?php
namespace App\ViewModels;

use App\Models\History\HistoryTimeSlot;
use DateInterval;
use DateTime;

class TicketsHistoryCategoryViewModel extends TicketsCategoryBaseViewModel
{
    public ?array $time_slots;
    public ?int $first_day_offset;
    public ?int $last_day_offset;

    public function __construct(?array $time_slots, ?int $first_day_offset, ?int $last_day_offset, ?int $current_page, ?int $total_page_number)
    {
        $this->time_slots = $time_slots;
        $this->first_day_offset = $first_day_offset;
        $this->last_day_offset = $last_day_offset;

        parent::__construct('history', 'History Tickets', '/history', 'No history events found at this time.', $current_page, $total_page_number);
    }

    public function getDateStringFromOffset(int $offset) : string {
        $date = new DateTime();

        if($offset === 0) return 'Today - ' . $date->format('d.m.Y');

        $date->add(new DateInterval('P' . $offset . 'D'));

        return $date->format('l') . ' - ' . $date->format('d.m.Y');
    }
}
