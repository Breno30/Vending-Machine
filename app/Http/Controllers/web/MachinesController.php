<?php

namespace App\Http\Controllers\web;

use Illuminate\Support\Str;
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

    public function assignUuid(Request $request)
    {
        $uuid = Str::uuid();

        Machine::create([
            'uuid' => $uuid
        ]);

        return [
            'uuid' => $uuid,
            'machine-registration-link' => route('machines.register').'?uuid='.$uuid
        ];
    }

    public function register(Request $request)
    {
        $uuid = $request->input('uuid');

        if (!$uuid) {
            abort(404, 'uuid not found');
        }

        $machine = Machine::where(['uuid' => $uuid])->get()->first();

        if (!$machine) {
            abort(404, 'Machine not found');
        }

        return view('machines.register', ['uuid' => $uuid]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MachineRequest $request)
    {
        $uuid = $request->input('uuid');
        $machine = Machine::where(['uuid' => $uuid])->get()->first();

        if (!$machine) {
            abort(404, 'Machine not found');
        }

        $ownerId = auth()->user()->id;
        $ownerData = ['owner_id' => $ownerId];

        $requestData = array_merge($request->all(), $ownerData);

        $machine->fill($requestData);
        $machine->save();

        return view('machines.show', ['machine' => $machine]);
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
