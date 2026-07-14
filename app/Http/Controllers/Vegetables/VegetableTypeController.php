<?php

namespace App\Http\Controllers\Vegetables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vegetables\VegetableType;
use Illuminate\Validation\Rule;

class VegetableTypeController extends Controller
{

     /**
     * Display a listing of vegetable types.
     */
    public function index(Request $request)
    {
        $query = VegetableType::query();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('variety', 'LIKE', "%{$search}%")
                    ->orWhere('unit', 'LIKE', "%{$search}%");

            });
        }

        $vegetables = $query
            ->latest()
            ->paginate($request->input('perPage', 10));

        return response()->json($vegetables);
    }


     /**
     * Store a newly created vegetable type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'max:255',
                'unique:vegetable_types,name'
            ],

            'variety' => 'nullable|max:255',

            'unit' => 'required|max:100',

            'description' => 'nullable',

            'status' => 'required|boolean'

        ]);

        $vegetable = VegetableType::create($validated);

        return response()->json([

            'success' => true,

            'message' => 'Vegetable type created successfully.',

            'data' => $vegetable

        ], 201);
    }

     /**
     * Display the specified vegetable type.
     */
    public function show($id)
    {
        $vegetable = VegetableType::findOrFail($id);

        return response()->json($vegetable);
    }


     /**
     * Update the specified vegetable type.
     */
    public function update(Request $request, $id)
    {
        $vegetable = VegetableType::findOrFail($id);

        $validated = $request->validate([

            'name' => [

                'required',

                'max:255',

                Rule::unique('vegetable_types')
                    ->ignore($vegetable->id)

            ],

            'variety' => 'nullable|max:255',

            'unit' => 'required|max:100',

            'description' => 'nullable',

            'status' => 'required|boolean'

        ]);

        $vegetable->update($validated);

        return response()->json([

            'success' => true,

            'message' => 'Vegetable type updated successfully.',

            'data' => $vegetable

        ]);
    }

     /**
     * Remove the specified vegetable type.
     */
    public function destroy($id)
    {
        $vegetable = VegetableType::findOrFail($id);

        $vegetable->delete();

        return response()->json([

            'success' => true,

            'message' => 'Vegetable type deleted successfully.'

        ]);
    }

    /**
     * Return statistics for dashboard cards.
     */
    public function statistics()
    {
        return response()->json([

            'total' => VegetableType::count(),

            'active' => VegetableType::query()->where('status', true)->count(),

            'inactive' => VegetableType::query()->where('status', false)->count(),

            'units' => VegetableType::distinct('unit')->count('unit')

        ]);
    }




}
