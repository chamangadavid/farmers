<?php

namespace App\Http\Controllers\Chickens;

use App\Http\Controllers\Controller;
use App\Models\Chickens\ChickenBatch;
use App\Models\Chickens\ChickenSale;
use App\Models\Chickens\ChickenExpense;
use App\Models\MonthlyReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChickenReportsController extends Controller
{

    public function GetSalesReportIndexPage()
    {
        return Inertia::render('MyFarmer/Chickens/Reports');
    }
    
    public function dashboard(Request $request)
    {

        $filter = $request->filter ?? 'month';
        $salesQuery = ChickenSale::query();
        $expenseQuery = ChickenExpense::query();

        // Apply filters
        switch ($filter) {

            case 'today':
                $salesQuery->whereDate('sale_date', today());
                $expenseQuery->whereDate('expense_date', today());

            break;
            case 'week':
                $salesQuery->whereBetween('sale_date', [now()->startOfWeek(), now()->endOfWeek()]);
                $expenseQuery->whereBetween('expense_date', [now()->startOfWeek(), now()->endOfWeek()]);

            break;
            case 'custom':
                if ($request->filled('from') && $request->filled('to')) {
                    $salesQuery->whereBetween('sale_date', [$request->from, $request->to]);
                    $expenseQuery->whereBetween('expense_date', [$request->from, $request->to]);
                }

            break;

            default:
                $salesQuery->whereMonth('sale_date', now()->month)->whereYear('sale_date', now()->year);
                $expenseQuery->whereMonth('expense_date', now()->month)->whereYear('expense_date', now()->year);

            break;
        }

        $sales = $salesQuery->latest()->get();
        $expenses = $expenseQuery->latest()->get();

        // Summary
        $summary = [

            'total_birds' => ChickenBatch::sum('batch_size'),
            'active_batches' => ChickenBatch::where('status', '!=', 'Completed')->count(),
            'sales_today' => ChickenSale::whereDate('sale_date', today())->sum('total_amount'),
            'monthly_revenue' => $sales->sum('total_amount'),
            'monthly_profit' => $sales->sum('total_amount') - $expenses->sum('amount')

        ];

        // Batch Performance
        $batches = ChickenBatch::with('sales', 'expenses')->get()
            ->map(function ($batch) {

                return [

                    'batch_number' => $batch->batch_number,
                    'batch_size' => $batch->batch_size,
                    'mortality_percentage' => $batch->batch_size > 0 ? round(($batch->mortality / $batch->batch_size) * 100, 2) : 0,
                    'feed_cost' => $batch->expenses->where('item', 'Feed')->sum('amount'),
                    'sales' => $batch->sales->sum('total_amount'),
                    'expenses' => $batch->expenses->sum('amount'),
                    'profit' => $batch->sales->sum('total_amount') - $batch->expenses->sum('amount')

                ];

            });


        $revenueChart = (clone $salesQuery)
            ->selectRaw('DATE(sale_date) as period')
            ->selectRaw('SUM(total_amount) revenue')
            ->groupBy('period')
            ->orderBy('period')
            ->get();


        $expenseChart = (clone $expenseQuery)
            ->selectRaw('item')
            ->selectRaw('SUM(amount) total')
            ->groupBy('item')
            ->get();



        // Mortality Chart
        $mortalityChart = ChickenBatch::get()
            ->map(function ($batch) {

                return [
                    'batch' => $batch->batch_number,
                    'mortality' => $batch->batch_size > 0 ? round(($batch->mortality / $batch->batch_size) * 100, 2) : 0

                ];

            });


        // Sales By Batch
        $salesByBatch = ChickenBatch::with('sales')->get()
            ->map(function ($batch) {

                return [

                    'batch' => $batch->batch_number,
                    'sales' => $batch->sales->sum('total_amount')
                ];

            });

        // Profit Loss
        $profitLoss = [

            'revenue' => $sales->sum('total_amount'),
            'expenses' => $expenses->sum('amount'),
            'profit' => $sales->sum('total_amount') - $expenses->sum('amount')

        ];

        return response()->json([

            'summary' => $summary,
            'batches' => $batches,
            'sales' => $sales,
            'expenses' => $expenses,
            'profitLoss' => $profitLoss,
            'charts' => [

                'revenue' => $revenueChart,
                'expenses' => $expenseChart,
                'mortality' => $mortalityChart,
                'salesByBatch' => $salesByBatch

            ]

        ]);
    }

    public function downloadPdf(Request $request)
    {
        // Load sales and expenses with batch relationship
        $sales = ChickenSale::with('batch')->latest()->get();
        $expenses = ChickenExpense::with('batch')->latest()->get();

        // Dashboard Summary
        $summary = [

            'total_birds' => ChickenBatch::sum('batch_size'),
            'active_batches' => ChickenBatch::where('status', '!=', 'Completed')->count(),
            'sales_today' => ChickenSale::whereDate('sale_date', today())->sum('total_amount'),
            'monthly_revenue' => $sales->sum('total_amount'),
            'monthly_profit' => $sales->sum('total_amount') - $expenses->sum('amount')
        ];

        // Batch Performance
        $batchPerformance = ChickenBatch::with('sales', 'expenses')->get()
            ->map(function ($batch) {

                return [

                    'batch_number' => $batch->batch_number,
                    'batch_size' => $batch->batch_size,
                    'mortality_percentage' => $batch->batch_size > 0 ? round(($batch->mortality / $batch->batch_size) * 100, 2) : 0,
                    'feed_cost' => $batch->expenses->where('item', 'Feed')->sum('amount'),
                    'sales' => $batch->sales->sum('total_amount'),
                    'expenses' => $batch->expenses->sum('amount'),
                    'profit' => $batch->sales->sum('total_amount') - $batch->expenses->sum('amount')
                ];

            });

        // Profit & Loss
        $profitLoss = [

            'revenue' => $sales->sum('total_amount'),
            'expenses' => $expenses->sum('amount'),
            'profit' => $sales->sum('total_amount') - $expenses->sum('amount')

        ];

        // Monthly Summary
        $monthlySummary = MonthlyReport::orderBy('year')->orderBy('month')->get();

        // Generate PDF
        $pdf = Pdf::loadView('reports.chickens',[
                'summary' => $summary,
                'batchPerformance' => $batchPerformance,
                'sales' => $sales,
                'expenses' => $expenses,
                'profitLoss' => $profitLoss,
                'monthlySummary' => $monthlySummary,
            ]
        );

        return $pdf->download('Chicken_Farm_Report.pdf');
    }

    public function pdf()
    {

        $data=$this->dashboard(request())->getData();
        $pdf=Pdf::loadView('chicken.reports', compact('data'));

        return $pdf->download('chicken-report.pdf');


    }



}
