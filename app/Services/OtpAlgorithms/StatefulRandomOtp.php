<?php

namespace App\Services\OtpAlgorithms;

use Illuminate\Support\Facades\Hash;

/**
 * Baseline Stateful Random OTP implementation representing the existing Pineus Tilu system.
 * 
 * Generation: random_int(100000, 999999) + Hash::make()
 * Verification: Hash::check()
 * 
 * This class operates 100% in-memory without accessing MySQL database, Eloquent models, or external services.
 */
class StatefulRandomOtp implements OtpAlgorithmInterface
{
    /**
     * Get the human-readable algorithm name.
     */
    public function getName(): string
    {
        return 'Stateful Random OTP (Baseline)';
    }

    /**
     * Generate 6-digit random OTP using random_int() and hash it using Hash::make() (Bcrypt).
     *
     * @return array{otp: string, state: string}
     */
    public function generate(): array
    {
        $otp = (string) random_int(100000, 999999);
        $hash = Hash::make($otp);

        return [
            'otp' => $otp,
            'state' => $hash,
        ];
    }

    /**
     * Verify the OTP input against the stored Bcrypt hash using Hash::check().
     *
     * @param string $otp
     * @param mixed $state The Bcrypt hash string generated during generate()
     * @return bool
     */
    public function verify(string $otp, mixed $state): bool
    {
        if (!is_string($state) || $state === '') {
            return false;
        }

        return Hash::check($otp, $state);
    }
}
