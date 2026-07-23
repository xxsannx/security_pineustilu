<?php

namespace App\Services\Benchmark;

use RuntimeException;

/**
 * Benchmark Visualization Chart Generator Service.
 * 
 * Reads statistics.json and raw_data.csv artifacts to generate a 100% standalone,
 * interactive HTML visualization dashboard (index.html) using Chart.js.
 * 
 * Features:
 * - All Algorithm Comparison (Baseline Bcrypt vs OATH)
 * - OATH-Only Microsecond Comparison (HOTP vs TOTP vs OCRA)
 * - Bar Charts (Mean Generate & Verify Latencies)
 * - Distribution Line Charts (50 Raw Iterations - All vs OATH-Only)
 * - Five-Number Summary Box Plot Component (Min, Q1, Median, Q3, Max)
 * - Summary Statistics Tables & Environment Reproducibility Badge
 * - Clean Light Academic Theme (Print/Screenshot ready for Thesis Chapter 4)
 */
class ChartGeneratorService
{
    /**
     * Generate HTML dashboard from benchmark artifacts.
     *
     * @param string|null $jsonPath
     * @param string|null $csvPath
     * @param string|null $outputHtmlPath
     * @return string Path to generated index.html file
     */
    public function generateCharts(
        ?string $jsonPath = null,
        ?string $csvPath = null,
        ?string $outputHtmlPath = null
    ): string {
        $baseDir = $this->getBenchmarkStorageDir();
        $jsonPath = $jsonPath ?? $baseDir . '/statistics.json';
        $csvPath = $csvPath ?? $baseDir . '/raw_data.csv';
        $outputDir = $baseDir . '/charts';
        $outputHtmlPath = $outputHtmlPath ?? $outputDir . '/index.html';

        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $statistics = $this->readStatisticsJson($jsonPath);
        $rawRecords = $this->readRawCsv($csvPath);

        $htmlContent = $this->buildHtmlDashboard($statistics, $rawRecords);

        file_put_contents($outputHtmlPath, $htmlContent);

        return $outputHtmlPath;
    }

    /**
     * Read statistics.json artifact.
     */
    public function readStatisticsJson(string $jsonPath): array
    {
        if (!file_exists($jsonPath)) {
            throw new RuntimeException("Statistics JSON file not found at: {$jsonPath}");
        }

        $content = file_get_contents($jsonPath);
        $data = json_decode($content, true);

        if (!is_array($data) || !isset($data['results'])) {
            throw new RuntimeException("Invalid statistics JSON format in: {$jsonPath}");
        }

        return $data;
    }

    /**
     * Read raw_data.csv artifact.
     */
    public function readRawCsv(string $csvPath): array
    {
        if (!file_exists($csvPath)) {
            throw new RuntimeException("Raw CSV data file not found at: {$csvPath}");
        }

        $handle = fopen($csvPath, 'r');
        if (!$handle) {
            throw new RuntimeException("Unable to open raw CSV file at: {$csvPath}");
        }

        $header = fgetcsv($handle); // Skip header row
        $records = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) >= 4) {
                $records[] = [
                    'iteration' => (int) $row[0],
                    'algorithm' => $row[1],
                    'generate_ms' => (float) $row[2],
                    'verify_ms' => (float) $row[3],
                ];
            }
        }

        fclose($handle);
        return $records;
    }

    /**
     * Build full standalone HTML dashboard content with embedded JS and CSS.
     */
    private function buildHtmlDashboard(array $statistics, array $rawRecords): string
    {
        $env = $statistics['environment'] ?? [];
        $results = $statistics['results'] ?? [];

        $envJson = json_encode($env, JSON_PRETTY_PRINT);
        $resultsJson = json_encode($results, JSON_PRETTY_PRINT);
        $rawRecordsJson = json_encode($rawRecords);

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Analisis Performa Algoritma OTP - Pineus Tilu</title>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #475569;
            --accent-blue: #0284c7;
            --accent-green: #16a34a;
            --accent-purple: #9333ea;
            --accent-red: #dc2626;
            --border-color: #cbd5e1;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
        body { background-color: var(--bg-color); color: var(--text-main); padding: 2rem; line-height: 1.5; }
        
        header { margin-bottom: 2rem; border-bottom: 2px solid var(--border-color); padding-bottom: 1rem; display: flex; justify-content: space-between; align-items: flex-end; }
        header h1 { font-size: 1.8rem; font-weight: 700; color: #0369a1; }
        header p { color: var(--text-muted); font-size: 0.95rem; margin-top: 0.2rem; }

        .meta-badge { background: #0284c7; color: #fff; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        
        .grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .card { background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 10px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .card h2 { font-size: 1.15rem; color: #0f172a; font-weight: 700; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; }

        .chart-container { position: relative; height: 320px; width: 100%; }

        table { width: 100%; border-collapse: collapse; margin-top: 1rem; font-size: 0.88rem; }
        th, td { padding: 0.65rem 0.85rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background-color: #f1f5f9; color: #0f172a; font-weight: 700; border-bottom: 2px solid #cbd5e1; }
        tr:nth-child(even) { background-color: #f8fafc; }
        tr:hover { background-color: #e2e8f0; }
        
        .tag-baseline { color: #dc2626; font-weight: bold; }
        .tag-oath { color: #16a34a; font-weight: bold; }

        .footer { text-align: center; color: var(--text-muted); margin-top: 3rem; font-size: 0.85rem; border-top: 1px solid var(--border-color); padding-top: 1rem; }
        
        @media print {
            body { background: #fff; color: #000; padding: 0; }
            .card { background: #fff; color: #000; border: 1px solid #ccc; box-shadow: none; page-break-inside: avoid; }
            th { background: #eee; color: #000; }
        }
    </style>
</head>
<body>

    <header>
        <div>
            <h1>Dashboard Analisis Performa Algoritma OTP</h1>
            <p>Studi Perbandingan Kuantitatif: Stateful Random (Baseline Bcrypt) vs HOTP (RFC 4226) vs TOTP (RFC 6238) vs OCRA (RFC 6287)</p>
        </div>
        <div>
            <span class="meta-badge">Pineus Tilu Skripsi Research</span>
        </div>
    </header>

    <!-- SECTION 1: BAR CHARTS (MEAN LATENCY) -->
    <div class="grid-2">
        <div class="card">
            <h2>Mode 1: Perbandingan Seluruh Algoritma (Mean Latency) <span style="font-size:0.8rem; color:#64748b; font-weight:normal;">Skala Linear (ms)</span></h2>
            <div class="chart-container">
                <canvas id="barAllCanvas"></canvas>
            </div>
        </div>

        <div class="card">
            <h2>Mode 2: Perbandingan Khusus Standar OATH (HOTP vs TOTP vs OCRA) <span style="font-size:0.8rem; color:#16a34a; font-weight:normal;">Skala Mikrodetik (ms)</span></h2>
            <div class="chart-container">
                <canvas id="barOathCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- SECTION 2: DISTRIBUTION CHARTS (ALL vs OATH-ONLY) -->
    <div class="grid-2">
        <div class="card">
            <h2>Sebaran Latensi 50 Iterasi (Seluruh Algoritma)</h2>
            <div class="chart-container">
                <canvas id="distAllCanvas"></canvas>
            </div>
        </div>

        <div class="card">
            <h2>Sebaran Latensi 50 Iterasi Khusus Standar OATH <span style="font-size:0.8rem; color:#16a34a; font-weight:normal;">Detail Fluktuasi Mikrodetik</span></h2>
            <div class="chart-container">
                <canvas id="distOathCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- SECTION 3: FIVE-NUMBER SUMMARY (BOX PLOTS COMPONENT) -->
    <div class="grid-2">
        <div class="card">
            <h2>Five-Number Summary Generate Latency <span style="font-size:0.8rem; color:#64748b; font-weight:normal;">(Min, Q1, Median, Q3, Max)</span></h2>
            <div class="chart-container">
                <canvas id="boxGenerateCanvas"></canvas>
            </div>
        </div>

        <div class="card">
            <h2>Five-Number Summary Verify Latency <span style="font-size:0.8rem; color:#64748b; font-weight:normal;">(Min, Q1, Median, Q3, Max)</span></h2>
            <div class="chart-container">
                <canvas id="boxVerifyCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- SECTION 4: STATISTICS TABLE -->
    <div class="card" style="margin-bottom: 2rem;">
        <h2>Tabel Ringkasan Statistik Deskriptif (50 Iterasi Benchmark)</h2>
        <div style="overflow-x: auto;">
            <table id="statsTable">
                <thead>
                    <tr>
                        <th>Algoritma</th>
                        <th>Operasi</th>
                        <th>Samples</th>
                        <th>Mean (ms)</th>
                        <th>StdDev (ms)</th>
                        <th>CV (%)</th>
                        <th>Min (ms)</th>
                        <th>Q1 (ms)</th>
                        <th>Median (ms)</th>
                        <th>Q3 (ms)</th>
                        <th>Max (ms)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Injected via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECTION 5: REPRODUCIBILITY METADATA -->
    <div class="card">
        <h2>Environment & Reproducibility Metadata</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; font-size: 0.9rem; margin-top: 0.5rem;" id="envMetadata">
            <!-- Injected via JavaScript -->
        </div>
    </div>

    <div class="footer">
        <p>Aplikasi Web Pineus Tilu &copy; 2026 - Generasi Otomatis oleh <code>ChartGeneratorService</code></p>
    </div>

    <script>
        const envData = {$envJson};
        const resultsData = {$resultsJson};
        const rawRecordsData = {$rawRecordsJson};

        // Render Environment Metadata
        const envContainer = document.getElementById('envMetadata');
        envContainer.innerHTML = `
            <div><strong>PHP Version:</strong> \${envData.php_version || '8.2'}</div>
            <div><strong>Laravel Version:</strong> \${envData.laravel_version || '11.x'}</div>
            <div><strong>OS:</strong> \${envData.os || 'Windows'}</div>
            <div><strong>CPU Architecture:</strong> \${envData.cpu_architecture || 'x64'}</div>
            <div><strong>BCRYPT Cost:</strong> \${envData.bcrypt_cost || 12}</div>
            <div><strong>Executed At:</strong> \${envData.executed_at || '-'}</div>
        `;

        // Render Statistics Table Rows
        const tableBody = document.querySelector('#statsTable tbody');
        let tableRowsHtml = '';

        for (const [algo, ops] of Object.entries(resultsData)) {
            const isBaseline = algo.includes('Stateful');
            const tagClass = isBaseline ? 'tag-baseline' : 'tag-oath';

            ['generate', 'verify'].forEach(op => {
                const s = ops[op];
                tableRowsHtml += `
                    <tr>
                        <td class="\${tagClass}">\${algo}</td>
                        <td style="text-transform: capitalize; font-weight:600;">\${op}</td>
                        <td>\${s.samples}</td>
                        <td><strong>\${s.mean.toFixed(6)}</strong></td>
                        <td>\${s.std_dev.toFixed(6)}</td>
                        <td>\${s.cv_percent.toFixed(2)}%</td>
                        <td>\${s.min.toFixed(6)}</td>
                        <td>\${s.q1.toFixed(6)}</td>
                        <td>\${s.median.toFixed(6)}</td>
                        <td>\${s.q3.toFixed(6)}</td>
                        <td>\${s.max.toFixed(6)}</td>
                    </tr>
                `;
            });
        }
        tableBody.innerHTML = tableRowsHtml;

        // Colors
        const colors = {
            statefulGen: '#dc2626',
            statefulVer: '#b91c1c',
            hotpGen: '#0284c7',
            hotpVer: '#0369a1',
            totpGen: '#16a34a',
            totpVer: '#15803d',
            ocraGen: '#9333ea',
            ocraVer: '#7e22ce'
        };

        Chart.defaults.color = '#1e293b';
        Chart.defaults.font.family = "'Segoe UI', system-ui, sans-serif";

        const algosAll = Object.keys(resultsData);
        const algosOath = algosAll.filter(a => !a.includes('Stateful'));

        // 1. BAR CHART: ALL ALGORITHMS COMPARISON
        new Chart(document.getElementById('barAllCanvas'), {
            type: 'bar',
            data: {
                labels: algosAll,
                datasets: [
                    { label: 'Generate Mean (ms)', data: algosAll.map(a => resultsData[a].generate.mean), backgroundColor: '#dc2626' },
                    { label: 'Verify Mean (ms)', data: algosAll.map(a => resultsData[a].verify.mean), backgroundColor: '#0284c7' }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#0f172a', font: { weight: 'bold' } } },
                    tooltip: { callbacks: { label: ctx => ` \${ctx.dataset.label}: \${ctx.raw.toFixed(6)} ms` } }
                },
                scales: {
                    y: { title: { display: true, text: 'Waktu (ms)', color: '#0f172a', font: { weight: 'bold' } }, grid: { color: '#e2e8f0' }, ticks: { color: '#1e293b' } },
                    x: { ticks: { color: '#0f172a', font: { weight: 'bold' } }, grid: { color: '#e2e8f0' } }
                }
            }
        });

        // 2. BAR CHART: OATH ONLY COMPARISON
        new Chart(document.getElementById('barOathCanvas'), {
            type: 'bar',
            data: {
                labels: algosOath,
                datasets: [
                    { label: 'Generate Mean (ms)', data: algosOath.map(a => resultsData[a].generate.mean), backgroundColor: '#16a34a' },
                    { label: 'Verify Mean (ms)', data: algosOath.map(a => resultsData[a].verify.mean), backgroundColor: '#9333ea' }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#0f172a', font: { weight: 'bold' } } },
                    tooltip: { callbacks: { label: ctx => ` \${ctx.dataset.label}: \${ctx.raw.toFixed(6)} ms (\${(ctx.raw * 1000).toFixed(2)} μs)` } }
                },
                scales: {
                    y: { title: { display: true, text: 'Waktu (ms)', color: '#0f172a', font: { weight: 'bold' } }, grid: { color: '#e2e8f0' }, ticks: { color: '#1e293b' } },
                    x: { ticks: { color: '#0f172a', font: { weight: 'bold' } }, grid: { color: '#e2e8f0' } }
                }
            }
        });

        // 3. DISTRIBUTION LINE CHARTS (ALL vs OATH ONLY)
        const iterations = Array.from({length: 50}, (_, i) => i + 1);
        
        function getRawSeries(algoName, field) {
            return rawRecordsData
                .filter(r => r.algorithm === algoName)
                .map(r => r[field]);
        }

        // All Distribution
        new Chart(document.getElementById('distAllCanvas'), {
            type: 'line',
            data: {
                labels: iterations,
                datasets: algosAll.map((a, idx) => ({
                    label: a + ' (Generate)',
                    data: getRawSeries(a, 'generate_ms'),
                    borderWidth: 2,
                    tension: 0.1,
                    pointRadius: 2,
                    borderColor: [colors.statefulGen, colors.hotpGen, colors.totpGen, colors.ocraGen][idx]
                }))
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#0f172a', font: { weight: 'bold' } } },
                    tooltip: { callbacks: { label: ctx => ` \${ctx.dataset.label}: \${ctx.raw.toFixed(6)} ms` } }
                },
                scales: {
                    y: { title: { display: true, text: 'Generate Latency (ms)', color: '#0f172a', font: { weight: 'bold' } }, grid: { color: '#e2e8f0' }, ticks: { color: '#1e293b' } },
                    x: { title: { display: true, text: 'Iterasi (1-50)', color: '#0f172a', font: { weight: 'bold' } }, grid: { color: '#e2e8f0' }, ticks: { color: '#1e293b' } }
                }
            }
        });

        // OATH Only Distribution
        new Chart(document.getElementById('distOathCanvas'), {
            type: 'line',
            data: {
                labels: iterations,
                datasets: algosOath.map((a, idx) => ({
                    label: a + ' (Generate)',
                    data: getRawSeries(a, 'generate_ms'),
                    borderWidth: 2,
                    tension: 0.1,
                    pointRadius: 2,
                    borderColor: [colors.hotpGen, colors.totpGen, colors.ocraGen][idx]
                }))
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#0f172a', font: { weight: 'bold' } } },
                    tooltip: { callbacks: { label: ctx => ` \${ctx.dataset.label}: \${ctx.raw.toFixed(6)} ms (\${(ctx.raw * 1000).toFixed(2)} μs)` } }
                },
                scales: {
                    y: { title: { display: true, text: 'Generate Latency (ms)', color: '#0f172a', font: { weight: 'bold' } }, grid: { color: '#e2e8f0' }, ticks: { color: '#1e293b' } },
                    x: { title: { display: true, text: 'Iterasi (1-50)', color: '#0f172a', font: { weight: 'bold' } }, grid: { color: '#e2e8f0' }, ticks: { color: '#1e293b' } }
                }
            }
        });

        // 4. FIVE-NUMBER SUMMARY BOX PLOTS (MIN, Q1, MEDIAN, Q3, MAX)
        // Generate Box Plot Component
        new Chart(document.getElementById('boxGenerateCanvas'), {
            type: 'bar',
            data: {
                labels: algosAll,
                datasets: [
                    { label: 'Min (ms)', data: algosAll.map(a => resultsData[a].generate.min), backgroundColor: '#94a3b8' },
                    { label: 'Q1 (ms)', data: algosAll.map(a => resultsData[a].generate.q1), backgroundColor: '#38bdf8' },
                    { label: 'Median (ms)', data: algosAll.map(a => resultsData[a].generate.median), backgroundColor: '#0284c7' },
                    { label: 'Q3 (ms)', data: algosAll.map(a => resultsData[a].generate.q3), backgroundColor: '#4ade80' },
                    { label: 'Max (ms)', data: algosAll.map(a => resultsData[a].generate.max), backgroundColor: '#ef4444' }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#0f172a', font: { weight: 'bold' } } },
                    tooltip: { callbacks: { label: ctx => ` \${ctx.dataset.label}: \${ctx.raw.toFixed(6)} ms` } }
                },
                scales: {
                    y: { title: { display: true, text: 'Latency (ms)', color: '#0f172a', font: { weight: 'bold' } }, grid: { color: '#e2e8f0' }, ticks: { color: '#1e293b' } },
                    x: { ticks: { color: '#0f172a', font: { weight: 'bold' } }, grid: { color: '#e2e8f0' } }
                }
            }
        });

        // Verify Box Plot Component
        new Chart(document.getElementById('boxVerifyCanvas'), {
            type: 'bar',
            data: {
                labels: algosAll,
                datasets: [
                    { label: 'Min (ms)', data: algosAll.map(a => resultsData[a].verify.min), backgroundColor: '#94a3b8' },
                    { label: 'Q1 (ms)', data: algosAll.map(a => resultsData[a].verify.q1), backgroundColor: '#38bdf8' },
                    { label: 'Median (ms)', data: algosAll.map(a => resultsData[a].verify.median), backgroundColor: '#0284c7' },
                    { label: 'Q3 (ms)', data: algosAll.map(a => resultsData[a].verify.q3), backgroundColor: '#4ade80' },
                    { label: 'Max (ms)', data: algosAll.map(a => resultsData[a].verify.max), backgroundColor: '#ef4444' }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#0f172a', font: { weight: 'bold' } } },
                    tooltip: { callbacks: { label: ctx => ` \${ctx.dataset.label}: \${ctx.raw.toFixed(6)} ms` } }
                },
                scales: {
                    y: { title: { display: true, text: 'Latency (ms)', color: '#0f172a', font: { weight: 'bold' } }, grid: { color: '#e2e8f0' }, ticks: { color: '#1e293b' } },
                    x: { ticks: { color: '#0f172a', font: { weight: 'bold' } }, grid: { color: '#e2e8f0' } }
                }
            }
        });
    </script>
</body>
</html>
HTML;
    }

    /**
     * Get storage benchmark directory path safely.
     */
    private function getBenchmarkStorageDir(): string
    {
        if (function_exists('storage_path') && function_exists('app') && app()->bound('path.storage')) {
            return storage_path('app/benchmark');
        }

        return dirname(__DIR__, 3) . '/storage/app/benchmark';
    }
}
