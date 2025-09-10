<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Borrowing;

class CalculateFines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fines:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate fines for overdue borrowings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Calculating fines for overdue borrowings...');
        
        $overdueBorrowings = Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->get();
        
        $totalFines = 0;
        $updatedCount = 0;
        
        foreach ($overdueBorrowings as $borrowing) {
            $fineAmount = $borrowing->updateFine();
            
            if ($fineAmount > 0) {
                $totalFines += $fineAmount;
                $updatedCount++;
                
                $this->line("Updated fine for borrowing ID {$borrowing->id}: Rp " . number_format($fineAmount, 0, ',', '.'));
            }
        }
        
        $this->info("Updated {$updatedCount} borrowings with total fines: Rp " . number_format($totalFines, 0, ',', '.'));
        
        return Command::SUCCESS;
    }
}
