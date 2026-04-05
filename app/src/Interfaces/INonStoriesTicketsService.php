<?php
namespace App\Interfaces;

interface INonStoriesTicketsService
{
    public function getCategoryTickets(string $category): array;
}
