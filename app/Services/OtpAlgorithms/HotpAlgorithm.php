<?php

namespace App\Services\OtpAlgorithms;

/**
 * HOTP (HMAC-based One-Time Password) implementation conforming to RFC 4226.
 * 
 * Algorithm: HMAC-SHA1 + Dynamic Truncation
 * Secret Key: 20-byte binary key
 * Counter: 64-bit integer
 */
class HotpAlgorithm implements OtpAlgorithmInterface
{
    private string $secret;
    private int $counter;

    /**
     * @param string $secret 20-byte binary shared secret key
     * @param int $initialCounter Initial counter value
     */
    public function __construct(string $secret = '12345678901234567890', int $initialCounter = 0)
    {
        $this->secret = $secret;
        $this->counter = $initialCounter;
    }

    /**
     * Get the human-readable algorithm name.
     */
    public function getName(): string
    {
        return 'HOTP (RFC 4226)';
    }

    /**
     * Generate HOTP code for current counter state and advance counter by 1.
     *
     * @return array{otp: string, state: array{secret: string, counter: int}}
     */
    public function generate(): array
    {
        $currentCounter = $this->counter;
        $otp = self::calculateHotp($this->secret, $currentCounter);

        // Advance internal counter per invocation
        $this->counter++;

        return [
            'otp' => $otp,
            'state' => [
                'secret' => $this->secret,
                'counter' => $currentCounter,
            ],
        ];
    }

    /**
     * Verify OTP against the given secret & counter state using constant-time hash_equals().
     *
     * @param string $otp The input OTP code to verify
     * @param mixed $state Array containing 'secret' and 'counter'
     * @return bool
     */
    public function verify(string $otp, mixed $state): bool
    {
        if (!is_array($state) || !isset($state['secret'], $state['counter'])) {
            return false;
        }

        $expectedOtp = self::calculateHotp((string) $state['secret'], (int) $state['counter']);

        return hash_equals($expectedOtp, $otp);
    }

    /**
     * Core RFC 4226 HOTP calculation function.
     *
     * @param string $secret
     * @param int $counter
     * @param int $digits
     * @return string
     */
    public static function calculateHotp(string $secret, int $counter, int $digits = 6): string
    {
        // 1. Convert counter to 8-byte big-endian binary string
        $counterBin = pack('N2', 0, $counter);

        // 2. Calculate HMAC-SHA1
        $hmac = hash_hmac('sha1', $counterBin, $secret, true);

        // 3. Dynamic Truncation (RFC 4226 Section 5.4)
        $offset = ord($hmac[19]) & 0x0F;
        $dbc = (
            ((ord($hmac[$offset]) & 0x7F) << 24) |
            ((ord($hmac[$offset + 1]) & 0xFF) << 16) |
            ((ord($hmac[$offset + 2]) & 0xFF) << 8) |
            (ord($hmac[$offset + 3]) & 0xFF)
        );

        // 4. Compute D % 10^digits & zero pad
        $mod = 10 ** $digits;
        return str_pad((string) ($dbc % $mod), $digits, '0', STR_PAD_LEFT);
    }
}
