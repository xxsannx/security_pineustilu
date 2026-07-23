<?php

namespace App\Services\Benchmark;

use App\Services\OtpAlgorithms\HotpAlgorithm;
use App\Services\OtpAlgorithms\TotpAlgorithm;
use App\Services\OtpAlgorithms\OcraAlgorithm;

/**
 * Validates HOTP, TOTP, and OCRA algorithm implementations against Official RFC Test Vectors.
 * 
 * - HOTP: RFC 4226 Appendix D
 * - TOTP: RFC 6238 Appendix B
 * - OCRA: RFC 6287 Appendix C
 */
class RfcTestVectorValidator
{
    public const TEST_SECRET = '12345678901234567890'; // 20-byte ASCII secret

    /**
     * Run all RFC test vector validations and return overall status.
     *
     * @return bool True if all RFC test vectors pass 100% MATCH
     */
    public function validateAll(): bool
    {
        $results = $this->getResults();

        foreach ($results['details'] as $detail) {
            if (!$detail['valid']) {
                return false;
            }
        }

        return true;
    }

    /**
     * Run all test vector checks and return structured diagnostic details.
     *
     * @return array{
     *     success: bool,
     *     details: array<string, array{status: string, expected: string, actual: string, valid: bool}>
     * }
     */
    public function getResults(): array
    {
        // 1. HOTP RFC 4226 Appendix D (Secret: 20-byte, Counter: 0 -> Expected: 755224)
        $hotpActual = HotpAlgorithm::calculateHotp(self::TEST_SECRET, 0, 6);
        $hotpExpected = '755224';
        $hotpValid = hash_equals($hotpExpected, $hotpActual);

        // 2. TOTP RFC 6238 Appendix B (Secret: 20-byte, Timestamp: 59, 8-digit -> Expected: 94287082)
        $totpActual = TotpAlgorithm::calculateTotp(self::TEST_SECRET, 59, 8);
        $totpExpected = '94287082';
        $totpValid = hash_equals($totpExpected, $totpActual);

        // 3. OCRA RFC 6287 (Suite: OCRA-1:HOTP-SHA1-6:QN08, Secret: 20-byte, Challenge: 00000000 -> Expected: 713673)
        $ocraActual = OcraAlgorithm::calculateOcra(self::TEST_SECRET, '00000000', OcraAlgorithm::DEFAULT_SUITE, 6);
        $ocraExpected = '713673';
        $ocraValid = hash_equals($ocraExpected, $ocraActual);

        $allValid = $hotpValid && $totpValid && $ocraValid;

        return [
            'success' => $allValid,
            'details' => [
                'HOTP' => [
                    'status' => $hotpValid ? 'MATCH' : 'FAILED',
                    'expected' => $hotpExpected,
                    'actual' => $hotpActual,
                    'valid' => $hotpValid,
                ],
                'TOTP' => [
                    'status' => $totpValid ? 'MATCH' : 'FAILED',
                    'expected' => $totpExpected,
                    'actual' => $totpActual,
                    'valid' => $totpValid,
                ],
                'OCRA' => [
                    'status' => $ocraValid ? 'MATCH' : 'FAILED',
                    'expected' => $ocraExpected,
                    'actual' => $ocraActual,
                    'valid' => $ocraValid,
                ],
            ],
        ];
    }
}
