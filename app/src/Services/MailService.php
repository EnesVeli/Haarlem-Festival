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
        $mail->SMTPSecure = Config::MAIL_SMTPSECURE;
        $mail->Port = Config::MAIL_PORT;

        // Sender and recipient settings
        $mail->setFrom(Config::MAIL_EMAIL, 'Haarlem festival');
        $mail->addAddress('732456@student.inholland.nl', 'tim sadko');

        // Sending plain text email
        $mail->isHTML(false); // Set email format to plain text
        $mail->Subject = 'TextMessage';
        $mail->Body    = 'This is the plain text message body';

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
        $mail->SMTPSecure = Config::MAIL_SMTPSECURE;
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
}