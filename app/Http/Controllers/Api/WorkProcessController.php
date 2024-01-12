<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkProcessRequest;
use App\Http\Requests\UpdateWorkProcessRequest;
use App\Http\Resources\WorkProcessResource;
use App\Models\WorkProcess;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WorkProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workProcess = WorkProcessResource::collection(WorkProcess::paginate());
        return $workProcess;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkProcessRequest $request)
    {
        WorkProcess::create($request->validated());
        response()->json([
            'status' => 'success',
            'message' => 'work process created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkProcess $workProcess)
    {
        try {
            return response()->json($workProcess);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Work process not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkProcessRequest $request, WorkProcess $workProcess)
    {
        try {
            $workProcess->update($request->validated());
            return response()->json([
                'message' => 'work process updated successfully',
                'data' => $workProcess
            ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Work process not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkProcess $workProcess)
    {
        try {
            $workProcess->delete();
            return response()->json(['message' => 'work process deleted successfully']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Work process not found'], 404);
        }
    }
}
