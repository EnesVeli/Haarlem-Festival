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


    private function mustBeLoggedIn(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    private function validateCsrfToken(): void
    {
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        $postedToken = $_POST['csrf_token'] ?? '';

        if (!is_string($sessionToken) || !is_string($postedToken) || $sessionToken === '' || !hash_equals($sessionToken, $postedToken)) {
            http_response_code(403);
            echo '403 - Invalid CSRF token';
            exit;
        }
    }

    // GET /cart
    public function index(): void
    {
        $this->mustBeLoggedIn();

        $cart = $this->cartService->getCart((int)$_SESSION['user_id']);

        $success = $_SESSION['cart_success'] ?? null;
        $error   = $_SESSION['cart_error']   ?? null;
        unset($_SESSION['cart_success'], $_SESSION['cart_error']);

        require __DIR__ . '/../Views/Cart/index.php';
    }

    // POST /cart/add

    public function add(): void
    {
        $this->mustBeLoggedIn();
        $this->validateCsrfToken();

        try {
            $userId = (int)$_SESSION['user_id'];
            $eventId = (int)($_POST['event_id'] ?? 0);
            $quantity = (int)($_POST['quantity'] ?? 1);
            $ticketTypeId = (int)($_POST['ticket_type_id'] ?? 0);
            if ($eventId <= 0 || $ticketTypeId <= 0) {
                throw new \InvalidArgumentException('Invalid ticket selection.');
            }

            $customPriceInput = $_POST['custom_price'] ?? null;
            $customPrice = null;
            if ($customPriceInput !== null && $customPriceInput !== '') {
                if (!is_numeric($customPriceInput)) {
                    throw new \InvalidArgumentException('Invalid custom price.');
                }
                $customPrice = (float)$customPriceInput;
            }

            $this->cartService->addItemByTicketType(
                $userId,
                $eventId,
                $ticketTypeId,
                $quantity,
                $customPrice
            );
            $_SESSION['cart_success'] = "Ticket added to your cart!";
        } catch (\Exception $e) {
            $_SESSION['cart_error'] = $e->getMessage();
        }

        // Redirect back to where they came from, or to cart
        $redirect = (string)($_POST['redirect_back'] ?? '/cart');
        if ($redirect === '' || $redirect[0] !== '/' || str_starts_with($redirect, '//')) {
            $redirect = '/cart';
        }
        header('Location: ' . $redirect);
        exit;
    }

    // POST /cart/update 

    public function update(): void
    {
        $this->mustBeLoggedIn();
        $this->validateCsrfToken();

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

    //POST /cart/remove
    public function remove(): void
    {
        $this->mustBeLoggedIn();
        $this->validateCsrfToken();

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