<?php


namespace App\Http\Controllers;

use App\Models\InventoryType;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class InventoryTypeController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
        $inventoryTypes = InventoryType::all();

        return response()->json([
            'status' => 'success',
            'data' => $inventoryTypes,
        ], 200);
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $inventoryType = InventoryType::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Inventory Type created successfully.',
                'data' => $inventoryType,
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Database error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Display the specified resource
    public function show($id)
    {
        $inventoryType = InventoryType::find($id);

        if (!$inventoryType) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Inventory Type not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $inventoryType,
        ], 200);
    }

    // Update the specified resource in storage
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $inventoryType = InventoryType::findOrFail($id);

            $inventoryType->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Inventory Type updated successfully.',
                'data' => $inventoryType,
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Database error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Remove the specified resource from storage
    public function destroy($id)
    {
        try {
            $inventoryType = InventoryType::findOrFail($id);
            $inventoryType->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Inventory Type deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function search(Request $request)
    {
        try {
            $name = $request->query('name'); // Get the `name` parameter from the query string

            // Search for inventory types by name
            $inventoryTypes = InventoryType::query();

            if ($name) {
                $inventoryTypes = $inventoryTypes->where('name', 'LIKE', "%$name%");
            }

            $results = $inventoryTypes->get();

            return response()->json([
                'status' => 'success',
                'data' => $results,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }
}