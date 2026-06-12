<?php

namespace App\ViewModels\History;

class HistoryBookViewModel {
    public int $reservation_id;
    public string $time;
    public string $date;
    public int $empty_spots;
    public int $individual_cost;
    public int $family_cost;
}