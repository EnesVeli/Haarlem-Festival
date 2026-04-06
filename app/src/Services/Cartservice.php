<?php
namespace App\Services;

use App\Interfaces\ICartService;
use App\Repositories\CartRepository;
use Exception;

class CartService implements ICartService
{
    private const EVENT_TYPE_LABELS = [
        1 => 'jazz',
        2 => 'dance',
        3 => 'history',
        4 => 'yummy',
        5 => 'stories',
    ];

    private CartRepository $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function addItemByTicketType(
        int $userId,
        int $eventId,
        int $ticketTypeId,
        int $quantity,
        ?float $customPrice = null
    ): void {
        if ($quantity < 1) {
            throw new Exception('Quantity must be at least 1.');
        }

        $ticketType = $this->cartRepository->getTicketTypeById($ticketTypeId);
        if (!$ticketType) {
            throw new Exception('Ticket type not found.');
        }

        if ((int)$ticketType['event_id'] !== $eventId) {
            throw new Exception('Ticket type does not belong to this event.');
        }

        $isPayAsYouLike = (bool)$ticketType['is_pay_as_you_like'];
        $unitPrice = (float)$ticketType['price'];

        if ($isPayAsYouLike) {
            if ($customPrice !== null) {
                if ($customPrice < 0) {
                    throw new Exception('Price cannot be negative.');
                }
                $unitPrice = $customPrice;
            }
        } elseif ($customPrice !== null) {
            throw new Exception('Custom price is only allowed for pay-as-you-like tickets.');
        }

        if ($unitPrice < 0) {
            throw new Exception('Price cannot be negative.');
        }

        $eventType = $this->resolveEventTypeLabel($eventId);
        if ($eventType === null) {
            throw new Exception('Event not found.');
        }

        $existing = $this->cartRepository->findExisting($userId, $eventType, $eventId, 'single');
        if ($existing && abs((float)$existing['price'] - $unitPrice) < 0.0001) {
            $currentQty = (int)$existing['quantity'];
            $newQty = $currentQty + $quantity;
            $this->assertAvailabilityForQuantity($eventId, $newQty, $currentQty);
            $this->cartRepository->updateQuantity((int)$existing['cart_item_id'], $newQty);
            return;
        }

        $this->assertAvailabilityForQuantity($eventId, $quantity);
        $this->cartRepository->addItem($userId, $eventType, $eventId, 'single', $quantity, $unitPrice);
    }

    public function updateItem(int $userId, int $cartItemId, int $quantity): void
    {
        $item = $this->cartRepository->getItemById($cartItemId);

        if (!$item || (int)$item['user_id'] !== $userId) {
            throw new Exception('Cart item not found.');
        }

        if ($quantity < 1) {
            $this->cartRepository->removeItem($cartItemId);
            return;
        }

        $currentQty = (int)$item['quantity'];
        $eventId = (int)$item['event_id'];
        $this->assertAvailabilityForQuantity($eventId, $quantity, $currentQty);
        $this->cartRepository->updateQuantity($cartItemId, $quantity);
    }

    public function removeItem(int $userId, int $cartItemId): void
    {
        $item = $this->cartRepository->getItemById($cartItemId);

        if (!$item || (int)$item['user_id'] !== $userId) {
            throw new Exception('Cart item not found.');
        }

        $this->cartRepository->removeItem($cartItemId);
    }

    public function getCart(int $userId): array
    {
        $items = $this->cartRepository->getItemsByUser($userId);

        $total = 0.0;
        foreach ($items as &$item) {
            $item['subtotal'] = (float)$item['price'] * (int)$item['quantity'];
            $total += $item['subtotal'];
        }

        return [
            'items' => $items,
            'total' => $total,
            'count' => array_sum(array_column($items, 'quantity')),
        ];
    }

    public function getItemCount(int $userId): int
    {
        return $this->cartRepository->countItemsForUser($userId);
    }

    /**
     * Returns available sellable seats after the 90% single-ticket rule.
     */
    public function getSellableAvailability(int $eventId): int
    {
        $maxTickets = $this->cartRepository->getMaxTicketsForEvent($eventId);
        if ($maxTickets === null) {
            return 0;
        }

        $sellableCapacity = (int) floor($maxTickets * 0.9);
        $reservedInCarts = $this->cartRepository->getReservedCartQuantityByEvent($eventId);
        $paidTickets = $this->cartRepository->getPaidTicketQuantityByEvent($eventId);

        return max(0, $sellableCapacity - $reservedInCarts - $paidTickets);
    }
  
    private function assertAvailabilityForQuantity(int $eventId, int $requestedQuantity, int $existingQuantity = 0): void
    {
        $available = $this->getSellableAvailability($eventId) + $existingQuantity;
        
        if ($requestedQuantity > $available) {
            throw new Exception("Not enough tickets available. Only {$available} spots left.");
        }
    }

    private function resolveEventTypeLabel(int $eventId): ?string
    {
        $typeCode = $this->cartRepository->getEventTypeCodeByEventId($eventId);
        if ($typeCode === null) {
            return null;
        }

        return self::EVENT_TYPE_LABELS[$typeCode] ?? null;
    }
}