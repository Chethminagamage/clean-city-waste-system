<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CaptchaService
{
    /**
     * Verify Google reCAPTCHA v2
     */
    public function verifyRecaptcha($recaptchaResponse, $userIp = null)
    {
        $secretKey = env('RECAPTCHA_SECRET_KEY');
        
        if (empty($secretKey)) {
            Log::error('reCAPTCHA secret key not configured');
            return false;
        }
        
        if (empty($recaptchaResponse)) {
            return false;
        }
        
        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $recaptchaResponse,
                'remoteip' => $userIp ?? request()->ip()
            ]);
            
            $result = $response->json();
            
            if (isset($result['success']) && $result['success'] === true) {
                return true;
            }
            
            // Log errors for debugging
            if (isset($result['error-codes'])) {
                Log::warning('reCAPTCHA verification failed', [
                    'errors' => $result['error-codes']
                ]);
            }
            
            return false;
            
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification exception: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get reCAPTCHA site key
     */
    public function getSiteKey()
    {
        return env('RECAPTCHA_SITE_KEY');
    }
    
    /**
     * Verify CAPTCHA from session (for backward compatibility)
     */
    public function verifyCaptchaSession($recaptchaResponse)
    {
        // Now uses Google reCAPTCHA instead of math captcha
        return $this->verifyRecaptcha($recaptchaResponse);
    }
    
    /**
     * Set CAPTCHA session (for backward compatibility)
     */
    public function setCaptchaSession()
    {
        // reCAPTCHA doesn't need session storage, just return site key
        return $this->getSiteKey();
    }
}