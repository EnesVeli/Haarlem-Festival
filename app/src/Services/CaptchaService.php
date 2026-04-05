<?php
namespace App\Services;

use App\Config;

/**
 * Handles Google reCAPTCHA verification.
 */
class CaptchaService
{
    /**
     * Verifies registration captcha response token.
     */
    public function isValidRegistrationCaptcha(string $captchaResponse): bool
    {
        if ($captchaResponse === '') {
            return false;
        }

        $secretKey = Config::RECAPTCHA_SECRET_KEY;
        $verifyUrl = "https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captchaResponse}";
        $verifyResponse = file_get_contents($verifyUrl);

        if ($verifyResponse === false) {
            return false;
        }

        $responseData = json_decode($verifyResponse);
        return isset($responseData->success) && $responseData->success === true;
    }
}
