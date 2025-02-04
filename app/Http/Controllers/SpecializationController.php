<?php

namespace App\Http\Controllers;

use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class SpecializationController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
        $specializations = Specialization::all();

        return response()->json([
            'status' => 'success',
            'data' => $specializations,
        ], 200);
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $specialization = Specialization::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Specialization created successfully.',
                'data' => $specialization,
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
        $specialization = Specialization::find($id);

        if (!$specialization) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Specialization not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $specialization,
        ], 200);
    }

    // Update the specified resource in storage
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $specialization = Specialization::findOrFail($id);

            $specialization->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Specialization updated successfully.',
                'data' => $specialization,
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
    // public function destroy($id)
    // {
    //     try {
    //         $specialization = Specialization::findOrFail($id);
    //         $specialization->delete();

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Specialization deleted successfully.',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'fail',
    //             'message' => 'Something went wrong: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
    public function destroy($id)
    {
        try {
            $specialization = Specialization::findOrFail($id);
            $specialization->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Specialization deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }


    


    // Search for specializations by name
    public function search(Request $request)
    {
        $query = $request->input('name');

        $specializations = Specialization::where('name', 'LIKE', "%$query%")->get();

        return response()->json([
            'status' => 'success',
            'data' => $specializations,
        ], 200);
    }
}