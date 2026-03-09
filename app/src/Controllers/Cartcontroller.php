<?php
namespace App\Controllers;

use App\Services\CartService;

class CartController
{
    private CartService $cartService;

    public function __construct()
    {
        $this->cartService = new CartService();
    }

    // ── Guard ────────────────────────────────────────────────────────────────

    private function mustBeLoggedIn(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    // ── GET /cart ────────────────────────────────────────────────────────────

    public function index(): void
    {
        $this->mustBeLoggedIn();

        $cart = $this->cartService->getCart((int)$_SESSION['user_id']);

        $success = $_SESSION['cart_success'] ?? null;
        $error   = $_SESSION['cart_error']   ?? null;
        unset($_SESSION['cart_success'], $_SESSION['cart_error']);

        require __DIR__ . '/../Views/cart/index.php';
    }

    // ── POST /cart/add ───────────────────────────────────────────────────────

    public function add(): void
    {
        $this->mustBeLoggedIn();

        try {
            $this->cartService->addItem(
                (int)$_SESSION['user_id'],
                trim($_POST['event_type']   ?? ''),
                (int)($_POST['event_id']    ?? 0),
                trim($_POST['ticket_type']  ?? 'single'),
                (int)($_POST['quantity']    ?? 1),
                (float)($_POST['price']     ?? 0)
            );
            $_SESSION['cart_success'] = "Ticket added to your cart!";
        } catch (\Exception $e) {
            $_SESSION['cart_error'] = $e->getMessage();
        }

        // Redirect back to where they came from, or to cart
        $redirect = $_POST['redirect_back'] ?? '/cart';
        header('Location: ' . $redirect);
        exit;
    }

    // ── POST /cart/update ────────────────────────────────────────────────────

    public function update(): void
    {
        $this->mustBeLoggedIn();

        try {
            $this->cartService->updateItem(
                (int)$_SESSION['user_id'],
                (int)($_POST['cart_item_id'] ?? 0),
                (int)($_POST['quantity']     ?? 1)
            );
            $_SESSION['cart_success'] = "Cart updated.";
        } catch (\Exception $e) {
            $_SESSION['cart_error'] = $e->getMessage();
        }

        header('Location: /cart');
        exit;
    }

    // ── POST /cart/remove ────────────────────────────────────────────────────

    public function remove(): void
    {
        $this->mustBeLoggedIn();

        try {
            $this->cartService->removeItem(
                (int)$_SESSION['user_id'],
                (int)($_POST['cart_item_id'] ?? 0)
            );
            $_SESSION['cart_success'] = "Item removed from cart.";
        } catch (\Exception $e) {
            $_SESSION['cart_error'] = $e->getMessage();
        }

        header('Location: /cart');
        exit;
    }
}