<?php

namespace App\Http\Controllers\Vegetables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vegetables\VegetableHarvest;
use App\Models\Vegetables\VegetableProduction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;


class VegetableHarvestController extends Controller
{
    
    public function index(Request $request)
    {
        $search = $request->search;

        $query = VegetableHarvest::with([
            'production.vegetableType'
        ]);

        if ($search) {

            $query->whereHas('production', function ($q) use ($search) {

                $q->where('batch_number', 'like', "%{$search}%")
                  ->orWhereHas('vegetableType', function ($type) use ($search) {

                      $type->where('name', 'like', "%{$search}%");

                  });

            });

        }

        $harvests = $query
            ->latest()
            ->paginate($request->perPage ?? 10);

        return response()->json($harvests);
    }

    public function statistics()
    {
        $totalHarvests = VegetableHarvest::count();

        $totalHarvested = VegetableHarvest::sum('quantity');

        $todayHarvest = VegetableHarvest::whereDate(
            'harvest_date',
            today()
        )->sum('quantity');

        $weekHarvest = VegetableHarvest::whereBetween(
            'harvest_date',
            [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]
        )->sum('quantity');

        $monthHarvest = VegetableHarvest::whereMonth(
            'harvest_date',
            now()->month
        )->whereYear(
            'harvest_date',
            now()->year
        )->sum('quantity');

        $estimatedValue = VegetableHarvest::sum('estimated_value');

        $waste = VegetableHarvest::sum('waste');

        $gradeA = VegetableHarvest::where('grade', 'Grade A')
            ->sum('quantity');

        $gradeB = VegetableHarvest::where('grade', 'Grade B')
            ->sum('quantity');

        $averageYield = round(
            VegetableHarvest::avg('quantity'),
            2
        );

        return response()->json([

            'total' => $totalHarvests,

            'harvested' => $totalHarvested,

            'today' => $todayHarvest,

            'week' => $weekHarvest,

            'month' => $monthHarvest,

            'estimated_value' => $estimatedValue,

            'waste' => $waste,

            'grade_a' => $gradeA,

            'grade_b' => $gradeB,

            'average_yield' => $averageYield

        ]);
    }

    public function productions()
    {
        return VegetableProduction::with('vegetableType')
            ->where('status', '!=', 'Completed')
            ->orderBy('batch_number')
            ->get();
    }


    public function store(Request $request)
    {
        $validated = $request->validate([

            'vegetable_production_id' => 'required|exists:vegetable_productions,id',

            'harvest_date' => 'required|date',

            'grade' => 'nullable|string|max:100',

            'quantity' => 'required|numeric|min:0',

            'unit_price' => 'required|numeric|min:0',

            'waste' => 'nullable|numeric|min:0',

            'harvested_by' => 'nullable|string|max:255',

            'notes' => 'nullable|string'

        ]);

        $validated['estimated_value'] =
            $validated['quantity'] * $validated['unit_price'];

        $harvest = VegetableHarvest::create($validated);

        return response()->json([

            'message' => 'Harvest recorded successfully.',

            'harvest' => $harvest

        ]);
    }

    public function show(VegetableHarvest $vegetableHarvest)
    {
        return $vegetableHarvest->load(
            'production.vegetableType'
        );
    }

    public function update(Request $request, VegetableHarvest $vegetableHarvest)
    {
        $validated = $request->validate([

            'vegetable_production_id' => 'required|exists:vegetable_productions,id',

            'harvest_date' => 'required|date',

            'grade' => 'nullable|string|max:100',

            'quantity' => 'required|numeric|min:0',

            'unit_price' => 'required|numeric|min:0',

            'waste' => 'nullable|numeric|min:0',

            'harvested_by' => 'nullable|string|max:255',

            'notes' => 'nullable|string'

        ]);

        $validated['estimated_value'] =
            $validated['quantity'] * $validated['unit_price'];

        $vegetableHarvest->update($validated);

        return response()->json([

            'message' => 'Harvest updated successfully.',

            'harvest' => $vegetableHarvest

        ]);
    }

    public function destroy(VegetableHarvest $vegetableHarvest)
    {
        $vegetableHarvest->delete();

        return response()->json([

            'message' => 'Harvest deleted successfully.'

        ]);
    }

    public function remainingYield($productionId)
    {
        $production = VegetableProduction::with('harvests')
            ->findOrFail($productionId);

        $harvested = $production->harvests->sum('quantity');

        $remaining = $production->expected_yield - $harvested;

        return response()->json([

            'expected' => $production->expected_yield,

            'harvested' => $harvested,

            'remaining' => max($remaining, 0)

        ]);
    }

    public function receipt(VegetableHarvest $harvest)
    {

        $harvest->load([
            'production.vegetableType'

        ]);


        return view('pdf.harvest-receipt', compact('harvest'));

    }

    public function downloadPdf(VegetableHarvest $harvest)
    {

        $harvest->load([
            'production.vegetableType'

        ]);

        $pdf = Pdf::loadView('pdf.harvest-receipt', compact('harvest'));

        return $pdf->download('Harvest-'.$harvest->id.'.pdf');

    }

    public function exportPdf()
{


    $harvests = VegetableHarvest::with([

        'production.vegetableType'

    ])

    ->latest()

    ->get();



    $pdf = Pdf::loadView(

        'pdf.harvest-report',

        compact('harvests')

    );


    return $pdf->download(

        'vegetable-harvest-report.pdf'

    );


}


}
