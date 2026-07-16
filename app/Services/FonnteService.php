<?php

namespace App\Services;

use App\Services\AuditLogService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    /**
     * Send an OTP message via Fonnte API.
     *
     * @param string $phone
     * @param string $otp
     * @return bool
     */
    public function sendOtp(string $phone, string $otp): bool
    {
        $apiKey = env('FONTTE_API_KEY');

        if (!$apiKey) {
            Log::error('Fonnte API key is missing. Please add FONTTE_API_KEY to your .env file.');
            return false;
        }

        $message = "Kode OTP Anda adalah: {$otp}. Berlaku selama 15 menit. Jangan berikan kode ini ke siapa pun.";

        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62', // Ensures international format for Indonesian numbers
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['status']) && $responseData['status'] === true) {
                    Log::info("OTP successfully sent to {$phone} via Fonnte.");
                    return true;
                } else {
                    Log::warning("Fonnte API responded with non-true status for {$phone}.", [
                        'response' => $responseData
                    ]);
                    
                    AuditLogService::log(
                        'whatsapp_otp_failed',
                        "WhatsApp OTP gagal terkirim ke {$phone}. Response: " . json_encode($responseData),
                        null,
                        'WARNING'
                    );
                    
                    return false;
                }
            }

            Log::error("Fonnte API request failed for {$phone}.", [
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            AuditLogService::log(
                'whatsapp_otp_failed',
                "WhatsApp OTP gagal terkirim ke {$phone}. HTTP Status: " . $response->status(),
                null,
                'WARNING'
            );
            
            return false;
        } catch (\Exception $e) {
            Log::error("Fonnte API Exception for {$phone}: " . $e->getMessage());

            AuditLogService::log(
                'whatsapp_otp_failed',
                "WhatsApp OTP gagal terkirim ke {$phone}. Exception: " . $e->getMessage(),
                null,
                'WARNING'
            );

            return false;
        }
    }
}
