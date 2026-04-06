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
        $qrImage  = !empty($ticket['qr_token'])
            ? (new \chillerlan\QRCode\QRCode())->render($ticket['qr_token'])
            : '';

        $dateTime = '';
        if (!empty($ticket['start_time'])) {
            $dateTime = date('D d M Y, H:i', strtotime($ticket['start_time']));
            if (!empty($ticket['end_time'])) {
                $dateTime .= ' – ' . date('H:i', strtotime($ticket['end_time']));
            }
        }

        $html = '
        <style>
            body { font-family: Arial, sans-serif; padding: 40px; color: #333; margin: 0; }
            .ticket { border: 2px solid #1b3a5c; border-radius: 12px; overflow: hidden; max-width: 500px; margin: 0 auto; }
            .ticket-header { background: #1b3a5c; color: #fff; padding: 20px 24px; }
            .ticket-header h1 { margin: 0; font-size: 20px; }
            .ticket-header p { margin: 4px 0 0; font-size: 12px; opacity: 0.8; }
            .ticket-body { display: table; width: 100%; }
            .ticket-info { display: table-cell; padding: 24px; vertical-align: top; width: 60%; }
            .ticket-qr { display: table-cell; padding: 24px; vertical-align: middle; text-align: center; width: 40%; border-left: 1px dashed #ddd; }
            .label { font-size: 10px; text-transform: uppercase; color: #999; letter-spacing: 1px; margin-bottom: 2px; }
            .value { font-size: 14px; color: #1a1a2e; font-weight: bold; margin-bottom: 14px; }
            .ticket-code { font-family: monospace; font-size: 13px; color: #8b1e1e; background: #fdf0f0; padding: 6px 10px; border-radius: 6px; display: inline-block; }
            img { width: 120px; height: 120px; }
        </style>
        <div class="ticket">
            <div class="ticket-header">
                <h1>Festival Haarlem</h1>
                <p>Your Event Ticket</p>
            </div>
            <div class="ticket-body">
                <div class="ticket-info">
                    <div class="label">Customer</div>
                    <div class="value">' . htmlspecialchars($customerName) . '</div>

                    <div class="label">Event</div>
                    <div class="value">' . htmlspecialchars($ticket['event_name'] ?? '') . '</div>

                    <div class="label">Venue</div>
                    <div class="value">' . htmlspecialchars($ticket['venue_name'] ?? '') . '</div>

                    <div class="label">Date & Time</div>
                    <div class="value">' . htmlspecialchars($dateTime) . '</div>

                    <div class="label">Ticket Code</div>
                    <div class="ticket-code">' . htmlspecialchars($ticket['ticket_code'] ?? '') . '</div>
                </div>
                <div class="ticket-qr">
                    <div class="label" style="margin-bottom:8px;">Scan at entrance</div>
                    ' . ($qrImage ? '<img src="' . $qrImage . '" alt="QR Code">' : '') . '
                </div>
            </div>
        </div>';

        return $this->makePdf($html);
    }

    public function generateInvoice(array $items, array $invoice): string
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