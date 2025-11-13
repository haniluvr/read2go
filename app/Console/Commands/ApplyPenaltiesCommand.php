<?php

namespace App\Console\Commands;

use App\Services\PenaltyService;
use Illuminate\Console\Command;

class ApplyPenaltiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'penalties:apply';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply penalties for overdue book loans';

    /**
     * Execute the console command.
     */
    public function handle(PenaltyService $penaltyService)
    {
        $this->info('Checking for overdue loans and applying penalties...');

        $appliedCount = $penaltyService->checkAndApplyPenalties();

        $this->info("Applied penalties to {$appliedCount} loan(s).");

        return Command::SUCCESS;
    }
}
