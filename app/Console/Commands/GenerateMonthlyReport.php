<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Chickens\ChickenSale;
use App\Models\Chickens\ChickenBatch;
use App\Models\Chickens\ChickenExpense;
use App\Models\MonthlyReport;
use Illuminate\Support\Facades\Log;

class GenerateMonthlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-monthly-reporty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command monlthy report generated';

    /**
     * Execute the console command.
     */

public function handle()
{

    // Previous month
    $month = now()->subMonth();


    //Sales
    $sales = ChickenSale::whereYear('sale_date', $month->year)
        ->whereMonth('sale_date', $month->month)->get();

        $revenue = $sales->sum('total_amount');
        $birdsSold = $sales->sum('quantity');


    //Expenses
    $expenses = ChickenExpense::whereYear('expense_date', $month->year)
        ->whereMonth('expense_date', $month->month)->get();

        $totalExpenses = $expenses->sum('amount');
        $feedCost = $expenses->where('item', 'Feed')->sum('amount');
        $medicineCost = $expenses->where('item', 'Medicine')->sum('amount');
        $transportCost = $expenses->where('item', 'Transport')->sum('amount');
        $labourCost = $expenses->where('item', 'Labour')->sum('amount');
        $otherCost = $expenses->whereNotIn('item', ['Feed','Medicine', 'Transport', 'Labour'])->sum('amount');


    //batch Statistics
    $totalBirds = ChickenBatch::sum('batch_size');
    $birdsRemaining = ChickenBatch::sum('birds_remaining');
    $activeBatches = ChickenBatch::query()->where('status', '!=', 'Completed')->count();
    $completedBatches = ChickenBatch::query()->where('status', 'Completed')->count();
    $mortality = ChickenBatch::sum('mortality');
    $mortalityPercentage = $totalBirds > 0 ? round(($mortality / $totalBirds) * 100, 2) : 0;


    //profit
    $profit = $revenue - $totalExpenses;

    /*
    |--------------------------------------------------------------------------
    | Log Report
    |--------------------------------------------------------------------------
    */

    Log::info("========================================");

    Log::info("Generating Monthly Report");

    Log::info([

        'Month' => $month->format('F Y'),

        'Revenue' => $revenue,

        'Expenses' => $totalExpenses,

        'Profit' => $profit,

        'Feed Cost' => $feedCost,

        'Medicine Cost' => $medicineCost,

        'Transport Cost' => $transportCost,

        'Labour Cost' => $labourCost,

        'Other Cost' => $otherCost,

        'Birds Sold' => $birdsSold,

        'Total Birds' => $totalBirds,

        'Birds Remaining' => $birdsRemaining,

        'Active Batches' => $activeBatches,

        'Completed Batches' => $completedBatches,

        'Mortality' => $mortality,

        'Mortality %' => $mortalityPercentage

    ]);

    /*
    |--------------------------------------------------------------------------
    | Save Monthly Snapshot
    |--------------------------------------------------------------------------
    */

    MonthlyReport::updateOrCreate(

        [
            'month' => $month->month,
            'year' => $month->year

        ],

        [

            'revenue' => $revenue,
            'expenses' => $totalExpenses,
            'profit' => $profit,
            'birds_sold' => $birdsSold,
            'total_birds' => $totalBirds,
            'birds_remaining' => $birdsRemaining,
            'feed_cost' => $feedCost,
            'medicine_cost' => $medicineCost,
            'transport_cost' => $transportCost,
            'labour_cost' => $labourCost,
            'other_cost' => $otherCost,
            'active_batches' => $activeBatches,
            'completed_batches' => $completedBatches,
            'mortality' => $mortality,
            'mortality_percentage' => $mortalityPercentage

        ]

    );

    Log::info("Monthly Report Saved Successfully");

    Log::info("========================================");

    $this->info("Monthly report generated successfully.");
}


}
