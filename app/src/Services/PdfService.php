<?php
namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
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

    public function generateTicket(array $ticket, string $customerName): string
    {
        $html = '
        <style>
            body { font-family: Arial, sans-serif; padding: 40px; color: #333; }
            h1 { color: #8b1a1a; }
            .label { font-weight: bold; color: #555; }
            .value { margin-bottom: 10px; }
            .barcode { margin-top: 30px; font-size: 11px; color: #888; word-break: break-all; }
        </style>
        <h1>Festival Haarlem — Your Ticket</h1>
        <hr>
        <p><span class="label">Customer:</span> ' . htmlspecialchars($customerName) . '</p>
        <p><span class="label">Event:</span> ' . htmlspecialchars($ticket['event_name']) . '</p>
        <p><span class="label">Venue:</span> ' . htmlspecialchars($ticket['venue_name']) . '</p>
        <p><span class="label">Date & Time:</span> ' . date('D d M Y, H:i', strtotime($ticket['start_time'])) . ' – ' . date('H:i', strtotime($ticket['end_time'])) . '</p>
        <p><span class="label">Ticket Type:</span> ' . htmlspecialchars($ticket['ticket_type_name']) . '</p>
        <div class="barcode">
            <span class="label">Ticket Code:</span><br>' . htmlspecialchars($ticket['barcode']) . '
        </div>';

        return $this->makePdf($html);
    }

    public function generateInvoice(array $order, array $items, array $invoice): string
    {
        $rows = '';
        $subtotal = 0;
        foreach ($items as $item) {
            $lineTotal  = $item['unit_price'] * $item['quantity'];
            $subtotal  += $lineTotal;
            $rows .= '<tr>
                <td>' . htmlspecialchars($item['event_name']) . '</td>
                <td>' . (int)$item['quantity'] . '</td>
                <td>€' . number_format($item['unit_price'], 2) . '</td>
                <td>€' . number_format($lineTotal, 2) . '</td>
            </tr>';
        }

        $vatPct    = (float)$invoice['vat_percentage'];
        $vatAmount = $subtotal * ($vatPct / 100);
        $total     = $subtotal + $vatAmount;

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
        <h1>Invoice — Festival Haarlem</h1>
        <hr>
        <p><span class="label">Invoice Number:</span> ' . htmlspecialchars($invoice['invoice_number']) . '</p>
        <p><span class="label">Invoice Date:</span> ' . date('d M Y', strtotime($invoice['invoice_date'])) . '</p>
        <p><span class="label">Payment Date:</span> ' . date('d M Y') . '</p>
        <p><span class="label">Customer:</span> ' . htmlspecialchars($invoice['client_name']) . '</p>
        <p><span class="label">Email:</span> ' . htmlspecialchars($invoice['client_address']) . '</p>
        <table>
            <tr>
                <th>Event</th><th>Qty</th><th>Unit Price</th><th>Total</th>
            </tr>
            ' . $rows . '
        </table>
        <div class="totals">
            <p>Subtotal: €' . number_format($subtotal, 2) . '</p>
            <p>VAT (' . $vatPct . '%): €' . number_format($vatAmount, 2) . '</p>
            <p><strong>Total: €' . number_format($total, 2) . '</strong></p>
        </div>';

        return $this->makePdf($html);
    }
}