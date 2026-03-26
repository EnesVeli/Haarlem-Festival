<?php
namespace App\Services;

use App\Repositories\CartRepository;
use Exception;
use PDO;

class CartService
{
    private CartRepository $cartRepository;

    public function __construct()
    {
        $this->cartRepository = new CartRepository();
    }

    // ── Add to cart ─────────────────────────────────────────────────────────

    public function addItem(
        int    $userId,
        string $eventType,
        int    $eventId,
        string $ticketType,
        int    $quantity,
        float  $price
    ): void {
        if ($quantity < 1) {
            throw new Exception("Quantity must be at least 1.");
        }

        $allowed = ['single', 'daypass', 'allaccess'];
        if (!in_array($ticketType, $allowed, true)) {
            throw new Exception("Invalid ticket type.");
        }

        // Check availability before adding
        $this->checkAvailability($eventType, $eventId, $quantity);

        // If the exact same ticket is already in the cart, increment instead
        $existing = $this->cartRepository->findExisting($userId, $eventType, $eventId, $ticketType);

        if ($existing) {
            $newQty = $existing['quantity'] + $quantity;
            $this->checkAvailability($eventType, $eventId, $newQty);
            $this->cartRepository->updateQuantity((int)$existing['cart_item_id'], $newQty);
        } else {
            $this->cartRepository->addItem($userId, $eventType, $eventId, $ticketType, $quantity, $price);
        }
    }

    // ── Update quantity ──────────────────────────────────────────────────────

    public function updateItem(int $userId, int $cartItemId, int $quantity): void
    {
        $item = $this->cartRepository->getItemById($cartItemId);

        if (!$item || (int)$item['user_id'] !== $userId) {
            throw new Exception("Cart item not found.");
        }

        if ($quantity < 1) {
            // Treat 0 as remove
            $this->cartRepository->removeItem($cartItemId);
            return;
        }

        $this->checkAvailability($item['event_type'], (int)$item['event_id'], $quantity);
        $this->cartRepository->updateQuantity($cartItemId, $quantity);
    }

    // ── Remove item ──────────────────────────────────────────────────────────

    public function removeItem(int $userId, int $cartItemId): void
    {
        $item = $this->cartRepository->getItemById($cartItemId);

        if (!$item || (int)$item['user_id'] !== $userId) {
            throw new Exception("Cart item not found.");
        }

        $this->cartRepository->removeItem($cartItemId);
    }

    // ── Get cart ─────────────────────────────────────────────────────────────

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

    // ── Availability check ───────────────────────────────────────────────────
    // Checks the correct ticket table based on event_type.
    // Only history_tickets exists right now — extend this as you add more events.

    private function checkAvailability(string $eventType, int $eventId, int $requested): void
    {
        $available = $this->getAvailableSpots($eventType, $eventId);

        if ($available === null) {
            // No ticket table for this event type yet — skip check
            return;
        }

        // Requirement: only 90% of availability can be used for single tickets
        $usable = (int) floor($available * 0.9);

        if ($requested > $usable) {
            throw new Exception("Not enough tickets available. Only {$usable} spots left.");
        }
    }

    /**
     * Returns the number of available spots for a given event.
     *
     * For 'stories' events the method:
     *   1. Reads max_tickets from the Event table.
     *   2. Subtracts tickets currently in CartItem for this event.
     *   3. Subtracts tickets in paid OrderItem rows (joined through Ticket_Type).
     *
     * The 90 % capacity rule is applied by checkAvailability(), not here.
     *
     * @param  string   $eventType  The event category (e.g. 'history', 'stories')
     * @param  int      $eventId    The primary key of the event
     * @return int|null             Available spots, or null if type is unknown
     */
    private function getAvailableSpots(string $eventType, int $eventId): ?int
    {
        $pdo = $this->cartRepository->getConnection();

        // ── Stories: capacity lives in the Event table ──────────────
        if ($eventType === 'stories') {

            // 1. Get the maximum capacity from the Event table
            $stmt = $pdo->prepare(
                "SELECT max_tickets FROM `Event` WHERE event_id = :id LIMIT 1"
            );
            $stmt->execute(['id' => $eventId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return null;
            }

            $maxTickets = (int) $row['max_tickets'];

            // 2. Count tickets sitting in carts (not yet paid)
            $stmt = $pdo->prepare(
                "SELECT COALESCE(SUM(quantity), 0)
                   FROM `CartItem`
                  WHERE event_type = 'stories'
                    AND event_id   = :id"
            );
            $stmt->execute(['id' => $eventId]);
            $inCarts = (int) $stmt->fetchColumn();

            // 3. Count tickets already paid (OrderItem joined through Ticket_Type)
            $stmt = $pdo->prepare(
                "SELECT COALESCE(SUM(oi.quantity), 0)
                   FROM `OrderItem` oi
                   JOIN `Ticket_Type` tt ON tt.type_id = oi.type_id
                   JOIN `Order`      o  ON o.order_id = oi.order_id
                  WHERE tt.event_id = :id
                    AND o.status    = 'paid'"
            );
            $stmt->execute(['id' => $eventId]);
            $paid = (int) $stmt->fetchColumn();

            return max(0, $maxTickets - $inCarts - $paid);
        }

        // ── Other event types: static mapping to legacy tables ─────
        $map = [
            'history' => ['table' => 'history_tickets', 'id_col' => 'id'],
            // 'jazz'  => ['table' => 'jazz_tickets',   'id_col' => 'id'],
        ];

        if (!isset($map[$eventType])) {
            return null;
        }

        $table = $map[$eventType]['table'];
        $idCol = $map[$eventType]['id_col'];

        $stmt = $pdo->prepare(
            "SELECT available_spots FROM `{$table}` WHERE `{$idCol}` = :id LIMIT 1"
        );
        $stmt->execute(['id' => $eventId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? (int) $row['available_spots'] : null;
    }
}