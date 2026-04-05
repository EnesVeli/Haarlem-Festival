<?php
namespace App\Interfaces;

interface ICartService
{
    public function addItemByTicketType(
        int $userId,
        int $eventId,
        int $ticketTypeId,
        int $quantity,
        ?float $customPrice = null
    ): void;

    public function updateItem(int $userId, int $cartItemId, int $quantity): void;

    public function removeItem(int $userId, int $cartItemId): void;

    public function getCart(int $userId): array;

    public function getItemCount(int $userId): int;

    public function getSellableAvailability(int $eventId): int;
}
