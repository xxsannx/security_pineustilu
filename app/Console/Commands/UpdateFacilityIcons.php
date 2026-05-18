<?php

namespace App\Console\Commands;

use App\Helpers\FacilityIconHelper;
use App\Models\Facility;
use Illuminate\Console\Command;

class UpdateFacilityIcons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facilities:update-icons {--dry-run : Show what would be updated without actually updating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update facility icons for facilities that are missing icons';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Scanning for facilities missing icons...');

        $facilities = Facility::whereNull('icon')
            ->orWhere('icon', '')
            ->get();

        if ($facilities->isEmpty()) {
            $this->info('All facilities already have icons!');
            return Command::SUCCESS;
        }

        $this->info("Found {$facilities->count()} facilities without icons.");

        $updated = 0;
        $notFound = [];

        foreach ($facilities as $facility) {
            $icon = FacilityIconHelper::findIcon($facility->name);

            if ($icon) {
                if ($this->option('dry-run')) {
                    $this->line("  Would update: {$facility->name} => {$icon}");
                } else {
                    $facility->update(['icon' => $icon]);
                    $this->line("  Updated: {$facility->name} => {$icon}");
                }
                $updated++;
            } else {
                $notFound[] = $facility->name;
            }
        }

        $this->newLine();
        $this->info("Updated: {$updated} facilities");

        if (!empty($notFound)) {
            $this->warn("Could not find icons for " . count($notFound) . " facilities:");
            foreach ($notFound as $name) {
                $this->line("  - {$name}");
            }
        }

        if ($this->option('dry-run')) {
            $this->newLine();
            $this->comment('This was a dry run. No changes were made.');
        }

        return Command::SUCCESS;
    }
}
