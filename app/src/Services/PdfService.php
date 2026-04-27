<?php
namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    private static ?PdfService $_instance = null;

    private function __construct(){}

    public static function getInstance() : PdfService {
        if(self::$_instance === null) self::$_instance = new PdfService();

        return self::$_instance;
    }

    private function makePdf(string $html): string
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output(); // raw PDF bytes
    }

    public function generateTicket(string $qr_token, string $quantity, string $event_name, string $address, string $date_string, string $ticket_code): string
    {
        $qrImage  = !empty($qr_token) ? (new \chillerlan\QRCode\QRCode())->render($qr_token) : '';

        $html = file_get_contents(__DIR__ . '/../Views/mail/pdf-ticket.html');

        $html = str_replace('@1', $quantity, $html);
        $html = str_replace('@2', $event_name, $html);
        $html = str_replace('@3', $address, $html);
        $html = str_replace('@4', $date_string, $html);
        $html = str_replace('@5', $ticket_code, $html);
        $html = str_replace('@6', $qrImage, $html);

        return $this->makePdf($html);
    }

    public function generateInvoice(Order $order, array $user): string
    {
        $rows = '';
        $subtotal = 0;

        foreach ($order->order_items as $item) {
            $lineTotal  = $item->price;
            $subtotal  += $lineTotal;
            $rows .= '<tr>
                <td>' . htmlspecialchars($item->booking->getEventName()) . '</td>
                <td>' . htmlspecialchars($item->booking->getQuantityString()) . '</td>
                <td>€' . number_format($item->price / 100, 2) . '</td>
            </tr>';
        }

        $vatPct    = number_format(OrderService::$VAT_RATE / 100, 2);
        $vatAmount = number_format(($order->total_price - $subtotal) / 100, 2);
        $total     = number_format($order->total_price / 100, 2);
        $subtotal     = number_format($subtotal / 100, 2);

        $html = '
        <style>
            body { font-family: Arial, sans-serif; padding: 40px; color: #333; }
            h1 { color: #8b1a1a; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background: #8b1a1a; color: white; padding: 8px; text-align: left; }
            td { padding: 8px; border-bottom: 1px solid #ddd; }
            .totals { margin-top: 20px; text-align: right; }
            .label { font-weight: bold; }
        </style>
        <h1>Invoice - Festival Haarlem</h1>
        <hr>
        <p><span class="label">Payment Date:</span> ' . $order->date->format('d M Y') . '</p>
        <p><span class="label">Customer:</span> ' . htmlspecialchars($user['name']) . '</p>
        <p><span class="label">Email:</span> ' . htmlspecialchars($user['email']) . '</p>
        <table>
            <tr>
                <th>Event</th><th>Qty</th><th>Total</th>
            </tr>
            ' . $rows . '
        </table>
        <div class="totals">
            <p>Subtotal: €' . $subtotal . '</p>
            <p>VAT (' . $vatPct . '%): €' . $vatAmount . '</p>
            <p><strong>Total: €' . $total . '</strong></p>
        </div>';

        /*
        <p><span class="label">Invoice Number:</span> ' . htmlspecialchars($invoice['invoice_number']) . '</p>
        */

        return $this->makePdf($html);
    }
}