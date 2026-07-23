<?php

namespace App\Services\OtpAlgorithms;

/**
 * OCRA (OATH Challenge-Response Algorithm) implementation conforming to RFC 6287.
 * 
 * Target Suite: OCRA-1:HOTP-SHA1-6:QN08
 * - OCRA-1: Standard RFC 6287 specification version
 * - HOTP-SHA1-6: HMAC-SHA1 crypto function with 6-digit dynamic truncation
 * - QN08: 8-digit numeric challenge query
 * 
 * Operating Scope: Challenge-Response subset without Counter, Password, Session, or Time.
 */
class OcraAlgorithm implements OtpAlgorithmInterface
{
    public const DEFAULT_SUITE = 'OCRA-1:HOTP-SHA1-6:QN08';

    private string $secret;
    private string $challenge;
    private string $suite;

    /**
     * @param string $secret 20-byte binary shared secret key
     * @param string $challenge 8-digit numeric challenge string (e.g. "12345678")
     * @param string $suite OCRA suite definition string
     */
    public function __construct(
        string $secret = '12345678901234567890',
        string $challenge = '12345678',
        string $suite = self::DEFAULT_SUITE
    ) {
        $this->secret = $secret;
        $this->challenge = $challenge;
        $this->suite = $suite;
    }

    /**
     * Get the human-readable algorithm name.
     */
    public function getName(): string
    {
        return 'OCRA (RFC 6287)';
    }

    /**
     * Generate OCRA 6-digit response code based on Secret Key & Fixed Challenge.
     *
     * @return array{otp: string, state: array{secret: string, challenge: string, suite: string}}
     */
    public function generate(): array
    {
        $otp = self::calculateOcra($this->secret, $this->challenge, $this->suite, 6);

        return [
            'otp' => $otp,
            'state' => [
                'secret' => $this->secret,
                'challenge' => $this->challenge,
                'suite' => $this->suite,
            ],
        ];
    }

    /**
     * Verify OCRA response against the given secret & challenge state using constant-time hash_equals().
     *
     * @param string $otp The input OCRA response to verify
     * @param mixed $state Array containing 'secret', 'challenge', and optional 'suite'
     * @return bool
     */
    public function verify(string $otp, mixed $state): bool
    {
        if (!is_array($state) || !isset($state['secret'], $state['challenge'])) {
            return false;
        }

        $suite = (string) ($state['suite'] ?? $this->suite);
        $expectedOtp = self::calculateOcra(
            (string) $state['secret'],
            (string) $state['challenge'],
            $suite,
            6
        );

        return hash_equals($expectedOtp, $otp);
    }

    /**
     * Core RFC 6287 OCRA calculation function for OCRA-1:HOTP-SHA1-6:QN08.
     *
     * @param string $secret
     * @param string $challenge 8-digit numeric challenge
     * @param string $suite
     * @param int $digits
     * @return string
     */
    public static function calculateOcra(
        string $secret,
        string $challenge,
        string $suite = self::DEFAULT_SUITE,
        int $digits = 6
    ): string {
        // 1. Format Question (Q) for QN08: 8-digit ASCII string right-padded with NUL (\x00) bytes to 128 bytes
        $qBuffer = str_pad($challenge, 128, "\x00", STR_PAD_RIGHT);

        // 2. Form DataInput = SuiteString + \x00 + QBuffer (RFC 6287 Section 5.1 & 5.2)
        $dataInput = $suite . "\x00" . $qBuffer;

        // 3. Compute HMAC-SHA1 raw binary hash
        $hmac = hash_hmac('sha1', $dataInput, $secret, true);

        // 4. Dynamic Truncation (RFC 4226 Section 5.4 / RFC 6287 Section 5.3)
        $offset = ord($hmac[19]) & 0x0F;
        $dbc = (
            ((ord($hmac[$offset]) & 0x7F) << 24) |
            ((ord($hmac[$offset + 1]) & 0xFF) << 16) |
            ((ord($hmac[$offset + 2]) & 0xFF) << 8) |
            (ord($hmac[$offset + 3]) & 0xFF)
        );

        // 5. Compute D % 10^digits & zero pad
        $mod = 10 ** $digits;
        return str_pad((string) ($dbc % $mod), $digits, '0', STR_PAD_LEFT);
    }
}
