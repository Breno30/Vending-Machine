<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use Illuminate\Http\Request;

class MachinesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Retrieve all machines
        $withProducts = $request->query('with-products') == 'true';
        $machines = $withProducts ?
            Machine::with('products')->get() :
            Machine::all();
        return response()->json(['data' => $machines]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Create a new machine
        $machine = Machine::create($request->all());

        // Add products to machine
        if ($request->has('products')) {
            foreach ($request->products as $product) {

                $machine
                    ->products()
                    ->attach($product['id'], ['quantity' => $product['quantity']]);
            }
        }

        return response()->json(['data' => $machine]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        // Retrieve a specific machine by ID
        $withProducts = $request->query('with-products') == 'true';

        $machine = $withProducts ?
            Machine::with('products')->findOrFail($id) :
            Machine::findOrFail($id);
        return response()->json(['data' => $machine]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Update a specific machine by ID
        $machine = Machine::findOrFail($id);
        $machine->update($request->all());
        return response()->json(['data' => $machine]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Delete a specific machine by ID
        $machine = Machine::findOrFail($id);
        $machine->delete();
        return response()->json(['message' => 'Machine Deleted']);
    }

    // Products
    /**
     * List products from machine
     */
    public function listProduct(Request $request, Machine $machine)
    {
        return response()->json(['data' => $machine->products]);
    }

    /**
     * Add product to the machine
     */
    public function addProduct(Request $request, Machine $machine)
    {
        $machine->products()->attach($request->id, ['quantity' => $request->quantity]);

        return response()->json(['message' => 'Product added to machine successfully']);
    }

    /**
     * Remove product from the machine
     */
    public function removeProduct(Request $request, Machine $machine)
    {
        $machine->products()->detach($request->id);

        return response()->json(['message' => 'Product removed from machine successfully']);
    }
}
