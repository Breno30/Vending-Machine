<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\api\MachinesController as ApiMachinesController;
use App\Http\Controllers\Controller;
use App\Http\Requests\MachineRequest;
use App\Models\Machine;
use Illuminate\Http\Request;

class MachinesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $machines = auth()->user()->machines;

        return view('machines.index', ['machines' => $machines]);
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
    public function show(Machine $machine)
    {
        $this->authorize('view', $machine);

        $machine->load('products');

        return view('machines.show')->with('machine', $machine);
    }

    /**
     * Update resource in storage.
     */
    public function update(Machine $machine, MachineRequest $request) {
        $label = $request->input('label');

        $machine->fill($request->all());

        $machine->save();

        return redirect()->route('machines.index');
    }

    /**
     * Show the form to edit the specified resource in storage.
     */
    public function edit(Machine $machine)
    {
        $this->authorize('edit', $machine);

        return view('machines.edit')->with('machine', $machine);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
