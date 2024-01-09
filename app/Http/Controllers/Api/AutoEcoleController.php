<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAutoEcoleRequest;
use App\Http\Requests\UpdateAutoEcoleRequest;
use App\Models\AutoEcole;

class AutoEcoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('gerant')->only('store');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAutoEcoleRequest $request)
    {
        $autoEcole = AutoEcole::create([
            'name' => $request->name,
            'gerant_id' => auth()->user()->id,
            'permis_list' => $request->permis_list,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Auto ecole created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(AutoEcole $autoEcole)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAutoEcoleRequest $request, AutoEcole $autoEcole)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AutoEcole $autoEcole)
    {
        //
    }
}
