<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAutoEcoleRequest;
use App\Http\Requests\UpdateAutoEcoleRequest;
use App\Models\AutoEcole;

use function PHPUnit\Framework\isEmpty;

class AutoEcoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('gerant');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $autoEcoles =  AutoEcole::all();
        return response()->json($autoEcoles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAutoEcoleRequest $request)
    {
        AutoEcole::create([
            'name' => $request->name,
            'gerant_id' => auth()->user()->id,
            'permis_list' => $request->permis_list,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Auto ecole created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(AutoEcole $autoEcole)
    {
        $autoEcole = AutoEcole::find($autoEcole)->first();
        $user = auth()->user();

        if ($user->hasRole("gerant") && $autoEcole->gerant_id === $user->id) {
            return response()->json($autoEcole);
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAutoEcoleRequest $request, AutoEcole $autoEcole)
    {
        $autoEcole = AutoEcole::find($autoEcole);
        $autoEcole->update([
            'name' => $request->name,
            'permis_list' => $request->permis_list,
        ]);
        return response()->json([
            'message' => 'Updated success',
            'data' => $autoEcole,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AutoEcole $autoEcole)
    {
        $autoEcole = AutoEcole::findOrFail($autoEcole);
        $autoEcole->delete();
        return response('Deleted Success', 204);
    }
}
