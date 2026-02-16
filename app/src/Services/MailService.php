<?php

namespace App\Services;

use Exception;

use PHPMailer\PHPMailer\PHPMailer;

class MailService
{
    public function sentMail()
    {
        $mail = new PHPMailer(true); // Enable exceptions

        // SMTP Configuration
        

        // Send the email
        if(!$mail->send())
        {
            throw new Exception($mail->ErrorInfo);
        } 
    }
}