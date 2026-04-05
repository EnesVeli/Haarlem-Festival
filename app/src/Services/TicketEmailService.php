<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use chillerlan\QRCode\QRCode;

class TicketEmailService
{
    public function sendTicketEmail(string $toEmail, string $customerName, array $ticket): bool
    {
        $mail = new PHPMailer(true);

        try {
            $qrDataUri = (new QRCode())->render($ticket['qr_token']);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'YOUR_GMAIL@gmail.com';
            $mail->Password = 'YOUR_16_CHARACTER_APP_PASSWORD';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('saidraghoua@gmail.com', 'Haarlem Festival');
            $mail->addAddress($toEmail, $customerName);

            $mail->isHTML(true);
            $mail->Subject = 'Your Haarlem Festival Ticket';

            $mail->Body = '
                <h2>Your Ticket Confirmation</h2>
                <p>Hello ' . htmlspecialchars($customerName) . ',</p>
                <p>Here is your ticket QR code:</p>
                <p><img src="' . $qrDataUri . '" alt="Ticket QR Code" style="width:250px;height:250px;"></p>
                <p><strong>Ticket code:</strong> ' . htmlspecialchars($ticket['qr_token']) . '</p>
            ';

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}