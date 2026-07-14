<?php

namespace App\Http\Controllers\Vegetables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Vegetables\VegetableProduction;
use App\Models\Vegetables\VegetableType;

class VegetableProductionController extends Controller
{
    //

    /**
     * Display productions
     */
    public function index(Request $request)
    {
        $query = VegetableProduction::with('vegetableType');

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('batch_number', 'LIKE', "%{$search}%")
                    ->orWhere('season', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->orWhereHas('vegetableType', function ($q) use ($search) {

                        $q->where('name', 'LIKE', "%{$search}%");

                    });

            });

        }

        return response()->json(

            $query->latest()
                ->paginate($request->input('perPage', 10))

        );
    }

     /**
     * Store production
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'vegetable_type_id' => 'required|exists:vegetable_types,id',

            'season' => 'required|string|max:255',

            'quarter' => 'required|in:Q1,Q2,Q3,Q4,Q5',

            'planting_date' => 'required|date',

            'expected_harvest_date' => 'required|date|after_or_equal:planting_date',

            'harvest_end_date' => 'nullable|date|after_or_equal:expected_harvest_date',

            'land_size' => 'required|numeric|min:0',

            'land_unit' => 'required|in:Acres,Hectares',

            'expected_yield' => 'required|numeric|min:0',

            'actual_yield' => 'nullable|numeric|min:0',

            'status' => 'required|in:Planning,Growing,Harvesting,Completed',

            'notes' => 'nullable|string'

        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate Batch Number
        |--------------------------------------------------------------------------
        */

        $vegetable = VegetableType::findOrFail($validated['vegetable_type_id']);

        $prefix = strtoupper(substr($vegetable->name, 0, 3));

        $lastBatch = VegetableProduction::query()->where('batch_number', 'LIKE', "{$prefix}-%")
            ->latest('id')
            ->first();

        if ($lastBatch) {

            $number = (int) substr($lastBatch->batch_number, -4) + 1;

        } else {

            $number = 1;

        }

        $validated['batch_number'] = sprintf(
            "%s-%04d",
            $prefix,
            $number
        );

        $production = VegetableProduction::create($validated);

        return response()->json([

            'success' => true,

            'message' => 'Vegetable production created successfully.',

            'data' => $production->load('vegetableType')

        ], 201);
    }

     /**
     * View production
     */
    public function show($id)
    {
        return response()->json(

            VegetableProduction::with('vegetableType')
                ->findOrFail($id)

        );
    }

     /**
     * Update production
     */
    public function update(Request $request, $id)
    {
        $production = VegetableProduction::findOrFail($id);

        $validated = $request->validate([

            'vegetable_type_id' => 'required|exists:vegetable_types,id',

            'season' => 'required|string|max:255',

            'quarter' => 'required|in:Q1,Q2,Q3,Q4',

            'planting_date' => 'required|date',

            'expected_harvest_date' => 'required|date|after_or_equal:planting_date',

            'harvest_end_date' => 'nullable|date|after_or_equal:expected_harvest_date',

            'land_size' => 'required|numeric|min:0',

            'land_unit' => 'required|in:Acres,Hectares',

            'expected_yield' => 'required|numeric|min:0',

            'actual_yield' => 'nullable|numeric|min:0',

            'status' => 'required|in:Planning,Growing,Harvesting,Completed',

            'notes' => 'nullable|string'

        ]);

        $production->update($validated);

        return response()->json([

            'success' => true,

            'message' => 'Vegetable production updated successfully.',

            'data' => $production->fresh()->load('vegetableType')

        ]);
    }

     /**
     * Delete production
     */
    public function destroy($id)
    {
        $production = VegetableProduction::findOrFail($id);

        $production->delete();

        return response()->json([

            'success' => true,

            'message' => 'Production deleted successfully.'

        ]);
    }

     /**
     * Dashboard statistics
     */
    public function statistics()
    {
        return response()->json([

            'total' => VegetableProduction::count(),

            'planning' => VegetableProduction::query()->where('status', 'Planning')->count(),

            'growing' => VegetableProduction::query()->where('status', 'Growing')->count(),

            'harvesting' => VegetableProduction::query()->where('status', 'Harvesting')->count(),

            'completed' => VegetableProduction::query()->where('status', 'Completed')->count(),

            'expected_yield' => VegetableProduction::sum('expected_yield'),

            'actual_yield' => VegetableProduction::sum('actual_yield')

        ]);
    }

     /**
     * Dropdown list
     */
    public function vegetableTypes()
    {
        return response()->json(

            VegetableType::query()->where('status', true)
                ->orderBy('name')
                ->get()

        );
    }


}
