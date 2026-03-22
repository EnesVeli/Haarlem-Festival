<?php

namespace App\Controllers;

use App\Framework\Session;
use App\Repositories\CartRepository;

class PaymentController
{
    private CartRepository $cartRepo;

    public function __construct()
    {
        $this->cartRepo = new CartRepository();
        $this->requireLogin();
    }

    private function requireLogin(): void
    {
        if (!Session::isLoggedIn()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: /login');
            exit;
        }
    }

    // GET /checkout  — show checkout page
    public function index(): void
    {
        $userId    = $_SESSION['user_id'];
        $cartItems = $this->cartRepo->getItemsByUser($userId);
        $cartTotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cartItems));

        require __DIR__ . '/../Views/payment/checkout.php';
    }

    // POST /checkout/process  — simulate payment and redirect to confirmation
    public function process(): void
    {
        $userId        = $_SESSION['user_id'];
        $paymentMethod = $_POST['payment_method'] ?? 'credit_card';

        // Get cart items
        $cartItems = $this->cartRepo->getItemsByUser($userId);
        if (empty($cartItems)) {
            header('Location: /cart');
            exit;
        }

        $cartTotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cartItems));

        // Create order record
        $orderId = $this->createOrder($userId, $paymentMethod, $cartTotal);

        // Clear cart
        $this->cartRepo->clearCart($userId);

        // Store order ID in session for confirmation page
        $_SESSION['last_order_id'] = $orderId;

        header('Location: /checkout/confirmation');
        exit;
    }

    // GET /checkout/confirmation
    public function confirmation(): void
    {
        $orderId = $_SESSION['last_order_id'] ?? null;
        if (!$orderId) {
            header('Location: /');
            exit;
        }
        require __DIR__ . '/../Views/payment/confirmation.php';
    }

    // ── helpers ───────────────────────────────────────────────────────────────

    private function createOrder(int $userId, string $paymentMethod, float $total): int
    {
        // Uses PDO directly since we don't have an OrderRepository yet
        $pdo = (new \App\Framework\Repository())->getConnection();

        $stmt = $pdo->prepare(
            "INSERT INTO `Order` (user_id, status, payment_method) 
             VALUES (:user_id, 'paid', :method)"
        );
        $stmt->execute([':user_id' => $userId, ':method' => $paymentMethod]);
        return (int)$pdo->lastInsertId();
    }
}