<?php

namespace App\Services\OtpAlgorithms;

/**
 * TOTP (Time-based One-Time Password) implementation conforming to RFC 6238.
 * 
 * TOTP is an extension of HOTP (RFC 4226) where the counter is derived from Unix Time.
 * Algorithm: HMAC-SHA1 + Dynamic Truncation
 * Time Step (X): 30 seconds
 * T0: 0
 * Formula: T = floor((UnixTimestamp - T0) / 30)
 */
class TotpAlgorithm implements OtpAlgorithmInterface
{
    private string $secret;
    private int $timeStep;
    private ?int $fixedTimestamp;

    /**
     * @param string $secret 20-byte binary shared secret key
     * @param int $timeStep Time-step window in seconds (default 30s)
     * @param int|null $fixedTimestamp Optional fixed timestamp for reproducible benchmarks (e.g. 1700000000)
     */
    public function __construct(
        string $secret = '12345678901234567890',
        int $timeStep = 30,
        ?int $fixedTimestamp = 1700000000
    ) {
        $this->secret = $secret;
        $this->timeStep = $timeStep;
        $this->fixedTimestamp = $fixedTimestamp;
    }

    /**
     * Get the human-readable algorithm name.
     */
    public function getName(): string
    {
        return 'TOTP (RFC 6238)';
    }

    /**
     * Generate TOTP code based on current or fixed timestamp.
     *
     * @return array{otp: string, state: array{secret: string, timestamp: int, time_step: int}}
     */
    public function generate(): array
    {
        $timestamp = $this->fixedTimestamp ?? time();
        $otp = self::calculateTotp($this->secret, $timestamp, 6, $this->timeStep);

        return [
            'otp' => $otp,
            'state' => [
                'secret' => $this->secret,
                'timestamp' => $timestamp,
                'time_step' => $this->timeStep,
            ],
        ];
    }

    /**
     * Verify TOTP against the given secret & timestamp state using constant-time hash_equals().
     *
     * @param string $otp The input OTP code to verify
     * @param mixed $state Array containing 'secret' and 'timestamp'
     * @return bool
     */
    public function verify(string $otp, mixed $state): bool
    {
        if (!is_array($state) || !isset($state['secret'], $state['timestamp'])) {
            return false;
        }

        $timeStep = (int) ($state['time_step'] ?? $this->timeStep);
        $expectedOtp = self::calculateTotp(
            (string) $state['secret'],
            (int) $state['timestamp'],
            6,
            $timeStep
        );

        return hash_equals($expectedOtp, $otp);
    }

    /**
     * Core RFC 6238 TOTP calculation function.
     *
     * @param string $secret
     * @param int $timestamp
     * @param int $digits
     * @param int $timeStep
     * @return string
     */
    public static function calculateTotp(
        string $secret,
        int $timestamp,
        int $digits = 6,
        int $timeStep = 30
    ): string {
        // Compute moving factor T = floor((timestamp - T0) / X)
        $timeCounter = (int) floor($timestamp / $timeStep);

        // Delegate to HOTP calculation using time counter
        return HotpAlgorithm::calculateHotp($secret, $timeCounter, $digits);
    }
}
