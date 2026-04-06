<?php

namespace App\Services;

use App\Config;
use Exception;

use PHPMailer\PHPMailer\PHPMailer;

class MailService
{
    public function sendTestMail()
    {
        $mail = new PHPMailer(true); // Enable exceptions

        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = Config::MAIL_HOST; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = Config::MAIL_USERNAME; // Your Mailtrap username
        $mail->Password = Config::MAIL_PASSWORD; // Your Mailtrap password
        $mail->SMTPSecure = 'tls';
        $mail->Port = Config::MAIL_PORT;

        // Sender and recipient settings
        $mail->setFrom(Config::MAIL_EMAIL, 'Haarlem festival');
        $mail->addAddress('@', 'test mail');

        // Message settings 
        $mail->isHTML(true); // Set email format to plain text
        $mail->Subject = 'Password Reset';
        $mail->Body    = '<h1>Send HTML Email using SMTP in PHP</h1><p>This is a test email I\'m sending using SMTP mail server with PHPMailer.</p>';
        $mail->AltBody = 'This is a test email I\'m sending using SMTP mail server with PHPMailer.';

        // Send the email
        if(!$mail->send())
        {
            throw new Exception($mail->ErrorInfo);
        } 
    }

    public function sendHTMLMail(string $receiver_email, string $receiver_name, string $subject, string $html, string $alt_text)
    {
        $mail = new PHPMailer(true); // Enable exceptions

        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = Config::MAIL_HOST; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = Config::MAIL_USERNAME; // Your Mailtrap username
        $mail->Password = Config::MAIL_PASSWORD; // Your Mailtrap password
        $mail->SMTPSecure = 'tls';
        $mail->Port = Config::MAIL_PORT;

        // Sender and recipient settings
        $mail->setFrom(Config::MAIL_EMAIL, 'Haarlem festival');
        $mail->addAddress($receiver_email, $receiver_name);

        // Sending plain text email
        $mail->isHTML(true); // Set email format to plain text
        $mail->Subject = $subject;
        $mail->Body    = $html;
        $mail->AltBody = $alt_text;

        // Send the email
        if(!$mail->send())
        {
            throw new Exception($mail->ErrorInfo);
        } 
    }

    public function sendPasswordReset(string $receiver_email, string $receiver_name, string $reset_key){ 
        $html = file_get_contents(__DIR__ . '/../Views/password-reset/mail-password-reset.html');

        $html = str_replace('@', 'http://127.0.0.1/password-reset-start?key=' . $reset_key, $html);

        $this->sendHTMLMail($receiver_email, $receiver_name, 'Password Reset', $html, 
        'This is your password reset link: https://localhost/password-reset-start?key=' . $reset_key . '. \n
        Do not give it to anyone.\nThe link will expire in 15 minutes.');
    } 
    public function sendOrderConfirmation(
        string $email,
        string $name,
        array  $ticketPdfs,
        string $invoicePdf,
        string $invoiceNumber,
        array  $tickets = []
    ): void {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = Config::MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = Config::MAIL_USERNAME;
        $mail->Password   = Config::MAIL_PASSWORD;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = Config::MAIL_PORT;

        $mail->setFrom(Config::MAIL_EMAIL, 'Festival Haarlem');
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = 'Your Festival Haarlem Tickets & Invoice';
        $mail->Body    = $this->buildOrderEmailHtml($name, $tickets);
        $mail->AltBody = 'Thank you for your order, ' . $name . '! Your tickets and invoice are attached as PDFs.';

        foreach ($ticketPdfs as $i => $pdf) {
            $mail->addStringAttachment($pdf, 'ticket-' . ($i + 1) . '.pdf', 'base64', 'application/pdf');
        }
        $mail->addStringAttachment($invoicePdf, $invoiceNumber . '.pdf', 'base64', 'application/pdf');

        if (!$mail->send()) {
            throw new Exception($mail->ErrorInfo);
        }
    }

    private function buildOrderEmailHtml(string $name, array $tickets): string
    {
        $ticketRows = '';
        foreach ($tickets as $i => $t) {
            $qrImage   = (new \chillerlan\QRCode\QRCode())->render($t['qr_token']);
            $eventName = htmlspecialchars($t['event_name'] ?? '');
            $venue     = htmlspecialchars($t['venue_name'] ?? '');
            $start     = !empty($t['start_time']) ? date('D d M Y, H:i', strtotime($t['start_time'])) : '';
            $code      = htmlspecialchars($t['ticket_code'] ?? '');

            $ticketRows .= '
            <tr>
                <td style="padding:16px; border-bottom:1px solid #eee; vertical-align:top;">
                    <strong style="font-size:15px; color:#1a1a2e;">' . $eventName . '</strong><br>
                    <span style="color:#666; font-size:13px;">' . $venue . '</span><br>
                    <span style="color:#666; font-size:13px;">' . $start . '</span><br>
                    <span style="font-family:monospace; font-size:13px; color:#8b1e1e; margin-top:6px; display:inline-block;">' . $code . '</span>
                </td>
                <td style="padding:16px; border-bottom:1px solid #eee; text-align:center; vertical-align:middle;">
                    <img src="' . $qrImage . '" alt="QR Code" style="width:120px; height:120px;">
                </td>
            </tr>';
        }

        return '
        <div style="font-family:Inter,Arial,sans-serif; max-width:600px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; border:1px solid #eee;">
            <div style="background:#1b3a5c; padding:32px 28px; text-align:center;">
                <h1 style="color:#fff; margin:0; font-size:22px;">Festival Haarlem</h1>
                <p style="color:rgba(255,255,255,0.8); margin:8px 0 0; font-size:14px;">Order Confirmation</p>
            </div>
            <div style="padding:28px;">
                <p style="font-size:16px; color:#333;">Hello <strong>' . htmlspecialchars($name) . '</strong>,</p>
                <p style="color:#555; font-size:14px;">Thank you for your order! Your tickets are listed below and also attached as PDFs together with your invoice.</p>

                <table style="width:100%; border-collapse:collapse; margin-top:20px;">
                    <thead>
                        <tr style="background:#f5f3ef;">
                            <th style="padding:12px 16px; text-align:left; font-size:13px; color:#555; font-weight:600;">Event</th>
                            <th style="padding:12px 16px; text-align:center; font-size:13px; color:#555; font-weight:600;">QR Code</th>
                        </tr>
                    </thead>
                    <tbody>' . $ticketRows . '</tbody>
                </table>

                <p style="color:#999; font-size:12px; margin-top:24px;">
                    Show your QR code at the entrance. Each ticket is valid for one person.<br>
                    See you at the festival! 🎶
                </p>
            </div>
            <div style="background:#f5f3ef; padding:16px 28px; text-align:center;">
                <p style="color:#aaa; font-size:11px; margin:0;">© 2025 Festival Haarlem · Haarlem, Netherlands</p>
            </div>
        </div>';
    }
    


    
      
}