<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Benchmark\OtpBenchmarkService;
use App\Services\Benchmark\ChartGeneratorService;
use App\Services\Benchmark\RfcTestVectorValidator;
use Throwable;

class BenchmarkOtpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'benchmark:otp
                            {--iterations=50 : Number of benchmark measurement iterations per algorithm}
                            {--warmup=5 : Number of warm-up iterations per algorithm}
                            {--chart : Automatically generate standalone HTML chart dashboard}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run isolated in-memory CPU latency benchmark for OTP algorithms (Stateful Random, HOTP, TOTP, OCRA)';

    private OtpBenchmarkService $benchmarkService;
    private ChartGeneratorService $chartGenerator;
    private RfcTestVectorValidator $validator;

    public function __construct(
        OtpBenchmarkService $benchmarkService,
        ChartGeneratorService $chartGenerator,
        RfcTestVectorValidator $validator
    ) {
        parent::__construct();
        $this->benchmarkService = $benchmarkService;
        $this->chartGenerator = $chartGenerator;
        $this->validator = $validator;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $iterations = (int) $this->option('iterations');
        $warmup = (int) $this->option('warmup');
        $generateChart = (bool) $this->option('chart');

        $this->displayHeader();

        try {
            // Step 1: RFC Test Vector Validation Display
            $this->info('1. Running RFC Test Vector Validation...');
            $rfcResults = $this->validator->getResults();

            $rfcRows = [];
            foreach ($rfcResults['details'] as $algo => $info) {
                $rfcRows[] = [
                    $algo,
                    $info['status'] === 'MATCH' ? '✓ MATCH' : '✗ FAILED',
                    $info['expected'],
                    $info['actual'],
                ];
            }

            $this->table(['Algorithm Standard', 'Status', 'Expected Vector', 'Actual Vector'], $rfcRows);

            if (!$rfcResults['success']) {
                $this->error('RFC Test Vector Validation Failed! Aborting benchmark.');
                return Command::FAILURE;
            }

            $this->info("✓ All RFC Test Vectors 100% MATCHED!\n");

            // Step 2: Execution Configuration Display
            $this->info('2. Benchmark Configuration:');
            $this->line("   - Iterations : {$iterations} per algorithm");
            $this->line("   - Warm-up    : {$warmup} per algorithm");
            $this->line("   - Mode       : In-Memory CPU Latency (Isolated)\n");

            // Step 3: Run Benchmark
            $this->info('3. Running CPU Latency Benchmark Loop...');
            $output = $this->benchmarkService->runBenchmark($iterations, $warmup);
            $this->info("✓ Benchmark completed successfully!\n");

            // Step 4: Summary Table Display
            $this->info('4. Benchmark Results Summary (Mean & Five-Number Summary in ms):');
            $summaryRows = [];

            foreach ($output['results'] as $algoName => $ops) {
                foreach (['generate', 'verify'] as $op) {
                    $s = $ops[$op];
                    $summaryRows[] = [
                        $algoName,
                        ucfirst($op),
                        sprintf('%.6f', $s['mean']),
                        sprintf('%.6f', $s['std_dev']),
                        sprintf('%.2f%%', $s['cv_percent']),
                        sprintf('%.6f', $s['min']),
                        sprintf('%.6f', $s['median']),
                        sprintf('%.6f', $s['max']),
                    ];
                }
            }

            $this->table(
                ['Algorithm', 'Operation', 'Mean (ms)', 'StdDev (ms)', 'CV (%)', 'Min (ms)', 'Median (ms)', 'Max (ms)'],
                $summaryRows
            );

            // Step 5: Optional HTML Chart Generation
            $chartPath = null;
            if ($generateChart) {
                $this->info("\n5. Generating Interactive HTML Chart Dashboard...");
                $chartPath = $this->chartGenerator->generateCharts(
                    $output['json_path'],
                    $output['csv_path']
                );
                $this->info('✓ Interactive Chart Dashboard generated!');
            }

            // Step 6: Artifact File Locations Output
            $this->displayArtifacts($output['csv_path'], $output['json_path'], $chartPath);

            return Command::SUCCESS;

        } catch (Throwable $e) {
            $this->error("\nAn error occurred during benchmark execution:");
            $this->error($e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Display ASCII Title Header.
     */
    private function displayHeader(): void
    {
        $this->line('===============================================================');
        $this->info('  OTP ALGORITHM BENCHMARK - PINEUS TILU RESEARCH');
        $this->line('===============================================================');
        $this->line('Baseline Stateful Random vs HOTP vs TOTP vs OCRA');
        $this->line('===============================================================' . PHP_EOL);
    }

    /**
     * Display generated artifact file paths.
     */
    private function displayArtifacts(string $csvPath, string $jsonPath, ?string $chartPath): void
    {
        $this->info("\n===============================================================");
        $this->info('  GENERATED ARTIFACT FILES (STORAGE/APP/BENCHMARK)');
        $this->line('===============================================================');
        $this->line("RAW DATA CSV : {$csvPath}");
        $this->line("STATS JSON   : {$jsonPath}");

        if ($chartPath) {
            $this->line("HTML CHART   : {$chartPath}");
        } else {
            $this->line("HTML CHART   : Pass --chart option to generate interactive dashboard");
        }

        $this->line('===============================================================' . PHP_EOL);
    }
}
