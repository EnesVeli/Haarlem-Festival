<?php
namespace App\Controllers;

use App\Interfaces\ICartService;

/**
 * Handles cart pages and cart mutations.
 */
class CartController extends BaseController
{
    private ICartService $cartService;

    /**
     * @param ICartService $cartService Cart business logic service
     */
    public function __construct(ICartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Requires authenticated user for cart actions.
     */
    private function mustBeLoggedIn(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    /**
     * Validates CSRF token for POST actions.
     */
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

    /**
     * Renders cart page.
     */
    public function index(): void
    {
        $this->mustBeLoggedIn();

        $cart = $this->cartService->getCart((int)$_SESSION['user_id']);

        $success = $this->popFlash('cart_success');
        $error = $this->popFlash('cart_error');

        $this->render('Cart/index', [
            'cart' => $cart,
            'success' => $success,
            'error' => $error,
            'csrfToken' => $this->ensureCsrfToken(),
        ]);
    }

    /**
     * Adds one ticket selection to cart.
     */
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

        $redirect = (string)($_POST['redirect_back'] ?? '/cart');
        if ($redirect === '' || $redirect[0] !== '/' || str_starts_with($redirect, '//')) {
            $redirect = '/cart';
        }
        $_SESSION['cart_count'] = $this->cartService->getItemCount((int)$_SESSION['user_id']);
        $this->redirect($redirect);
    }

    /**
     * Updates quantity for one cart item.
     */
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

        $_SESSION['cart_count'] = $this->cartService->getItemCount((int)$_SESSION['user_id']);
        $this->redirect('/cart');
    }

    /**
     * Removes one cart item.
     */
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

        $_SESSION['cart_count'] = $this->cartService->getItemCount((int)$_SESSION['user_id']);
        $this->redirect('/cart');
    }
}
