<?php

namespace App\Services\Benchmark;

use App\Services\OtpAlgorithms\OtpAlgorithmInterface;
use App\Services\OtpAlgorithms\StatefulRandomOtp;
use App\Services\OtpAlgorithms\HotpAlgorithm;
use App\Services\OtpAlgorithms\TotpAlgorithm;
use App\Services\OtpAlgorithms\OcraAlgorithm;
use RuntimeException;

/**
 * Isolated In-Memory OTP Benchmark Service.
 * 
 * Orchestrates pure CPU latency benchmarking for:
 * 1. Stateful Random OTP (Pineus Tilu Baseline)
 * 2. OATH HOTP (RFC 4226)
 * 3. OATH TOTP (RFC 6238)
 * 4. OATH OCRA (RFC 6287)
 * 
 * Operates 100% in-memory without MySQL DB, Eloquent, Fonnte, Mail, or HTTP requests.
 */
class OtpBenchmarkService
{
    private RfcTestVectorValidator $validator;
    private StatisticsCalculator $calculator;

    public function __construct(
        ?RfcTestVectorValidator $validator = null,
        ?StatisticsCalculator $calculator = null
    ) {
        $this->validator = $validator ?? new RfcTestVectorValidator();
        $this->calculator = $calculator ?? new StatisticsCalculator();
    }

    /**
     * Run full benchmark suite and export raw_data.csv and statistics.json artifacts.
     *
     * @param int $iterations Number of measurement iterations per algorithm (default 50)
     * @param int $warmUp Number of warm-up iterations per algorithm (default 5)
     * @return array{
     *     environment: array,
     *     results: array,
     *     raw_records: array,
     *     csv_path: string,
     *     json_path: string
     * }
     */
    public function runBenchmark(int $iterations = 50, int $warmUp = 5): array
    {
        // Step 1: Ensure output directory exists safely
        $outputDir = $this->getStorageBenchmarkPath();
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // Step 2: Academic Security Guard - Automated RFC Test Vector Validation
        // Benchmark is aborted immediately if RFC test vectors fail 100% match check.
        if (!$this->validator->validateAll()) {
            throw new RuntimeException('RFC Test Vector Validation Failed! Aborting benchmark execution.');
        }

        // Step 3: Instantiate the 4 algorithm strategies under test
        $algorithms = [
            new StatefulRandomOtp(),
            new HotpAlgorithm('12345678901234567890', 0),
            new TotpAlgorithm('12345678901234567890', 30, 1700000000),
            new OcraAlgorithm('12345678901234567890', '12345678'),
        ];

        $rawRecords = [];
        $generateLatencies = [];
        $verifyLatencies = [];

        // Step 4: Mitigate CPU Latency Noise by Disabling PHP Garbage Collector during loop
        gc_disable();

        try {
            foreach ($algorithms as $algorithm) {
                $algoName = $algorithm->getName();
                $generateLatencies[$algoName] = [];
                $verifyLatencies[$algoName] = [];

                // Step 5: Warm-up Phase (5 iterations per algorithm, discarded from statistics)
                // Ensures Zend Engine, Class Autoloader, OPcache, and CPU Cache are warmed up.
                $this->warmUpAlgorithm($algorithm, $warmUp);

                // Step 6: Measurement Loop (50 iterations per algorithm)
                for ($i = 1; $i <= $iterations; $i++) {
                    // Measure Generate Latency (hrtime nanoseconds converted to ms)
                    $genMeasurement = $this->measureGenerate($algorithm);
                    $genOtp = $genMeasurement['result']['otp'];
                    $genState = $genMeasurement['result']['state'];
                    $genMs = $genMeasurement['latency_ms'];

                    // Measure Verify Latency (hrtime nanoseconds converted to ms)
                    $verMeasurement = $this->measureVerify($algorithm, $genOtp, $genState);
                    $verMs = $verMeasurement['latency_ms'];

                    // Record raw data row
                    $rawRecords[] = [
                        'iteration' => $i,
                        'algorithm' => $algoName,
                        'generate_ms' => $genMs,
                        'verify_ms' => $verMs,
                    ];

                    $generateLatencies[$algoName][] = $genMs;
                    $verifyLatencies[$algoName][] = $verMs;
                }
            }
        } finally {
            // Re-enable Garbage Collector safely
            gc_enable();
        }

        // Step 7: Calculate Descriptive Statistics & Five-Number Summary
        $results = [];
        foreach ($algorithms as $algorithm) {
            $algoName = $algorithm->getName();
            $results[$algoName] = [
                'generate' => $this->calculator->calculate($generateLatencies[$algoName]),
                'verify' => $this->calculator->calculate($verifyLatencies[$algoName]),
            ];
        }

        // Step 8: Collect Environment Metadata
        $environment = $this->collectEnvironmentMetadata();

        // Step 9: Export Artifacts (CSV & JSON)
        $csvPath = $outputDir . '/raw_data.csv';
        $jsonPath = $outputDir . '/statistics.json';

        $this->saveRawCsv($rawRecords, $csvPath);
        $this->saveStatisticsJson($environment, $results, $jsonPath);

        return [
            'environment' => $environment,
            'results' => $results,
            'raw_records' => $rawRecords,
            'csv_path' => $csvPath,
            'json_path' => $jsonPath,
        ];
    }

    /**
     * Perform warm-up iterations to eliminate cold-start autoloader/OPcache overhead.
     */
    public function warmUpAlgorithm(OtpAlgorithmInterface $algorithm, int $count): void
    {
        for ($w = 0; $w < $count; $w++) {
            $res = $algorithm->generate();
            $algorithm->verify($res['otp'], $res['state']);
        }
    }

    /**
     * Measure OTP Generation latency in milliseconds using high-precision hrtime(true).
     *
     * @return array{result: array{otp: string, state: mixed}, latency_ms: float}
     */
    public function measureGenerate(OtpAlgorithmInterface $algorithm): array
    {
        $start = hrtime(true);
        $result = $algorithm->generate();
        $end = hrtime(true);

        $latencyMs = ($end - $start) / 1e6; // Convert nanoseconds to milliseconds

        return [
            'result' => $result,
            'latency_ms' => round($latencyMs, 6),
        ];
    }

    /**
     * Measure OTP Verification latency in milliseconds using high-precision hrtime(true).
     *
     * @return array{result: bool, latency_ms: float}
     */
    public function measureVerify(OtpAlgorithmInterface $algorithm, string $otp, mixed $state): array
    {
        $start = hrtime(true);
        $result = $algorithm->verify($otp, $state);
        $end = hrtime(true);

        $latencyMs = ($end - $start) / 1e6; // Convert nanoseconds to milliseconds

        return [
            'result' => $result,
            'latency_ms' => round($latencyMs, 6),
        ];
    }

    /**
     * Save raw benchmark data to CSV format.
     * 200 data rows (50 iterations x 4 algorithms).
     */
    public function saveRawCsv(array $rawRecords, string $filePath): void
    {
        $handle = fopen($filePath, 'w');
        if (!$handle) {
            throw new RuntimeException("Unable to open CSV file for writing at {$filePath}");
        }

        // Write CSV Header
        fputcsv($handle, ['Iteration', 'Algorithm', 'Generate_ms', 'Verify_ms']);

        // Write Data Rows
        foreach ($rawRecords as $row) {
            fputcsv($handle, [
                $row['iteration'],
                $row['algorithm'],
                sprintf('%.6f', $row['generate_ms']),
                sprintf('%.6f', $row['verify_ms']),
            ]);
        }

        fclose($handle);
    }

    /**
     * Save statistics summary and environment metadata to JSON format.
     */
    public function saveStatisticsJson(array $environment, array $results, string $filePath): void
    {
        $payload = [
            'environment' => $environment,
            'results' => $results,
        ];

        $jsonContent = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($jsonContent === false) {
            throw new RuntimeException("Failed to encode statistics payload to JSON.");
        }

        file_put_contents($filePath, $jsonContent);
    }

    /**
     * Collect environment reproducibility metadata.
     */
    public function collectEnvironmentMetadata(): array
    {
        $laravelVersion = '11.x';
        $bcryptCost = 12;

        if (function_exists('app') && app()->bound('path.storage')) {
            $laravelVersion = app()->version();
        }

        if (function_exists('config')) {
            $bcryptCost = config('hashing.bcrypt.rounds', 12);
        }

        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => $laravelVersion,
            'os' => PHP_OS_FAMILY . ' (' . php_uname('s') . ' ' . php_uname('r') . ')',
            'cpu_architecture' => (PHP_INT_SIZE * 8) . '-bit (' . php_uname('m') . ')',
            'bcrypt_cost' => $bcryptCost,
            'executed_at' => date('c'),
        ];
    }

    /**
     * Get storage benchmark directory path safely.
     */
    private function getStorageBenchmarkPath(): string
    {
        if (function_exists('storage_path') && function_exists('app') && app()->bound('path.storage')) {
            return storage_path('app/benchmark');
        }

        return dirname(__DIR__, 3) . '/storage/app/benchmark';
    }
}
