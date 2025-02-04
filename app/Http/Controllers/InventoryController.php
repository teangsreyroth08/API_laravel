<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        try {
            $perPage = $request->get('per_page', 10);

            $items = Inventory::query()
            ->with(['type:id,name'])
            ->orderBy('item_name', 'ASC')
            ->paginate($perPage);


            return response()->json([
                'data'    => $items,
                'message' => 'List all'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }

    }

    public function listByType(Request $request)
    {

        try {
            $type_id = $request->type_id;

            $items = Inventory::where('type_id', $type_id)
            ->with(['type:id,name'])
            ->orderBy('item_name', 'ASC')
            ->get();

            return response()->json([
                'data'    => $items,
                'message' => 'List all'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        //
    }
}
