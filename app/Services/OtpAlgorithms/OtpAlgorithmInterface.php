<?php

namespace App\Services\OtpAlgorithms;

interface OtpAlgorithmInterface
{
    /**
     * Get the human-readable algorithm name.
     */
    public function getName(): string;

    /**
     * Generate OTP code and any associated state (hash, counter, timestamp, etc.).
     *
     * @return array{otp: string, state: mixed}
     */
    public function generate(): array;

    /**
     * Verify the given OTP code against the generated state.
     *
     * @param string $otp The OTP code input to verify
     * @param mixed $state The state created during generate()
     * @return bool True if valid, false otherwise
     */
    public function verify(string $otp, mixed $state): bool;
}
