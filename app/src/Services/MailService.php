<?php

namespace App\Services;

use App\Config;
use App\Models\Order;
use App\Models\Ticket;
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

    /**
     * Send order confirmation with all of its tickets and the invoice as attachments.
     * @param string $email reciever email.
     * @param string $name reciever name.
     * @param string[] $ticketPdfs array of tickets pdfs as strings.
     * @param string $invoicePdf invoice pdf as a string.
     * @param Ticket[] $tickets array of tickets.
     * @throws Exception 
     * @return void
     */
    public function sendOrderConfirmation(string $email, string $name, array $ticketPdfs, string $invoicePdf, array $tickets
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
        $mail->addStringAttachment($invoicePdf, "invoice" . '.pdf', 'base64', 'application/pdf');

        if (!$mail->send()) {
            throw new Exception($mail->ErrorInfo);
        }
    }

    /**
     * Builds html for order confirmation email.
     * @param string $name email reciever naem.
     * @param Ticket[] $tickets array of order tickets.
     * @return string returns html of confirmation email.
     */
    private function buildOrderEmailHtml(string $name, array $tickets): string
    {
        $ticketRows = '';
        foreach ($tickets as $t) {
            $qrImage   = (new \chillerlan\QRCode\QRCode())->render($t->qr_token);
            $eventName = htmlspecialchars($t->order_item->booking->getEventName());
            $venue     = htmlspecialchars($t->order_item->booking->getAddressFull());
            $start     = $t->order_item->booking->getBookingStartDate()->format('d.m.Y H:i') . ' - ' . $t->order_item->booking->getBookingEndDate()->format('H:i');
            $code      = htmlspecialchars($t->code);

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

        $html = file_get_contents(__DIR__ . '/../Views/mail/order.html');

        $html = str_replace('@1', $name, $html);
        $html = str_replace('@2', $ticketRows, $html);

        return $html;
    }    
}