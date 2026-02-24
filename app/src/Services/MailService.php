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
        $mail->addAddress('tim.sadko@gmail.com', 'tim sadko');

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
        $this->sendHTMLMail($receiver_email, $receiver_name, 'Password Reset', '
        <div>This is your password reset <a href="https://localhost/password-reset-start?key=' . $reset_key . '">link</a></div>
        <div>Do not give it to anyone.</div>
        <div>The link will expire in 15 minutes.</div>
        ', 
        'This is your password reset link: https://localhost/password-reset-start?key=' . $reset_key . '. \n
        Do not give it to anyone.\nThe link will expire in 15 minutes.');
    }
}