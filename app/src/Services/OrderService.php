<?php
namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\TicketRepository;
use App\Repositories\InvoiceRepository;
class OrderService
{
    private OrderRepository   $orderRepo;
    private TicketRepository  $ticketRepo;
    private InvoiceRepository $invoiceRepo;
    private PdfService        $pdfService;
    private MailService       $mailService;

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

        // 2 — Save order items + generate one ticket per quantity unit
        $generatedTickets = [];

        foreach ($cartItems as $item) {
            $typeId    = $this->resolveTypeId($item);
            $quantity  = (int)$item['quantity'];
            $unitPrice = (float)$item['price'];

            $this->orderRepo->addOrderItem($orderId, $typeId, $quantity, $unitPrice);

            for ($i = 0; $i < $quantity; $i++) {
                $qrToken    = bin2hex(random_bytes(24));
                $ticketCode = 'HF-' . strtoupper(bin2hex(random_bytes(3)));

                // Persist ticket for scanning
                $this->ticketRepo->createTicket($userId, $typeId, $qrToken, $ticketCode);

                // Collect data for PDF + email (cart items already carry event info)
                $generatedTickets[] = [
                    'event_name'       => $item['event_name']  ?? 'Festival Event',
                    'venue_name'       => $item['venue_name']  ?? '',
                    'start_time'       => $item['event_start'] ?? '',
                    'end_time'         => $item['event_end']   ?? '',
                    'ticket_type_name' => 'Regular Ticket',
                    'unit_price'       => $unitPrice,
                    'qr_token'         => $qrToken,
                    'ticket_code'      => $ticketCode,
                ];
            }
        }

        // 3 — Mark order as paid
        $this->orderRepo->markPaid($orderId);

        // 4 — Create invoice record
        $total = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cartItems));
        $this->invoiceRepo->createInvoice($orderId, $total, 9.00, $user['name'], $user['email']);

        // 5 — Fetch invoice + order items for PDFs
        $invoice    = $this->invoiceRepo->getByOrder($orderId);
        $orderItems = $this->orderRepo->getOrderWithItems($orderId);

        // 6 — Generate one ticket PDF per ticket (with QR code)
        $ticketPdfs = [];
        foreach ($generatedTickets as $ticket) {
            $ticketPdfs[] = $this->pdfService->generateTicket($ticket, $user['name']);
        }

        // 7 — Generate invoice PDF
        $invoicePdf = $this->pdfService->generateInvoice($orderItems, $invoice);

        // 8 — Send single confirmation email with all PDFs + inline ticket summary
        $this->mailService->sendOrderConfirmation(
            $user['email'],
            $user['name'],
            $ticketPdfs,
            $invoicePdf,
            $invoice['invoice_number'],
            $generatedTickets
        );

        return $orderId;
    }

    // Resolves Ticket_Type.type_id from a cart item
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
