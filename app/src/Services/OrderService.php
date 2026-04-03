<?php
namespace App\Services;

use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TicketRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\UserRepository;

class OrderService
{
    private OrderRepository  $orderRepo;
    private TicketRepository $ticketRepo;
    private InvoiceRepository $invoiceRepo;
    private PdfService       $pdfService;
    private MailService      $mailService;

    public function __construct()
    {
        $this->orderRepo   = new OrderRepository();
        $this->ticketRepo  = new TicketRepository();
        $this->invoiceRepo = new InvoiceRepository();
        $this->pdfService  = new PdfService();
        $this->mailService = new MailService();
    }

    public function completeOrder(int $userId, array $cartItems, string $paymentMethod, array $user): int
    {
        // 1 — Create the order
        $orderId = $this->orderRepo->createOrder($userId, $paymentMethod);

        // 2 — Save order items + create one ticket per quantity
        foreach ($cartItems as $item) {
            $typeId    = $this->resolveTypeId($item);
            $quantity  = (int)$item['quantity'];
            $unitPrice = (float)$item['price'];

            $this->orderRepo->addOrderItem($orderId, $typeId, $quantity, $unitPrice);

            // Create one Ticket row per ticket purchased
            for ($i = 0; $i < $quantity; $i++) {
                $barcode = hash_hmac('sha256', $orderId . '-' . $typeId . '-' . $i . '-' . $userId, 'festival_secret_key');
                $this->ticketRepo->createTicket($orderId, $typeId, $barcode);
            }
        }

        // 3 — Mark order as paid
        $this->orderRepo->markPaid($orderId);

        // 4 — Create invoice record
        $total = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cartItems));
        $this->invoiceRepo->createInvoice($orderId, $total, 9.00, $user['name'], $user['email']);

        // 5 — Fetch full data for PDFs
        $tickets = $this->ticketRepo->getTicketsByOrder($orderId);
        $invoice = $this->invoiceRepo->getByOrder($orderId);
        $orderItems = $this->orderRepo->getOrderWithItems($orderId);

        // 6 — Generate PDFs
        $ticketPdfs = [];
        foreach ($tickets as $ticket) {
            $ticketPdfs[] = $this->pdfService->generateTicket($ticket, $user['name']);
        }
        $invoicePdf = $this->pdfService->generateInvoice([], $orderItems, $invoice);

        // 7 — Send email with all PDFs attached
        $this->mailService->sendOrderConfirmation(
            $user['email'],
            $user['name'],
            $ticketPdfs,
            $invoicePdf,
            $invoice['invoice_number']
        );

        return $orderId;
    }

    // CartItem has event_id + ticket_type, we need the matching Ticket_Type.type_id
    private function resolveTypeId(array $cartItem): int
    {
        $pdo  = $this->orderRepo->getConnection();
        $stmt = $pdo->prepare(
            "SELECT type_id FROM `Ticket_Type`
             WHERE event_id = :eid
             ORDER BY type_id ASC
             LIMIT 1"
        );
        $stmt->execute([':eid' => $cartItem['event_id']]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? (int)$row['type_id'] : 0;
    }
}