<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkProcessRequest;
use App\Http\Requests\UpdateWorkProcessRequest;
use App\Http\Resources\WorkProcessResource;
use App\Models\WorkProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workProcess = WorkProcess::paginate();
        return WorkProcessResource::collection($workProcess);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(StoreWorkProcessRequest $request)
    // {
    //     WorkProcess::create($request->validated());
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'work process created successfully'
    //     ], 201);
    // }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'auto_ecole_id' => 'exists:auto_ecoles,id',
            'steps' => ['required', 'array']
        ]);
        if ($validator->fails()) {
            return Helper::handleValidationErrors($validator);
        }
        try {
            $workProcess = new WorkProcess($validator->validate());
            $workProcess->save();
        } catch (\Throwable $th) {
            return Helper::handleExceptions($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($workProcess)
    {
        try {
            $workProcess = WorkProcess::find($workProcess);
            if (!$workProcess) {
                return Helper::handleNotFound("Wrok process not found:(");
            }
            return response()->json($workProcess);
        } catch (\Throwable $th) {
            return Helper::handleExceptions($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateWorkProcessRequest $request, WorkProcess $workProcess)
    // {
    //     try {
    //         $workProcess->update($request->validated());
    //         return response()->json([
    //             'message' => 'work process updated successfully',
    //             'data' => new WorkProcessResource($workProcess),
    //         ]);
    //     } catch (ModelNotFoundException $exception) {
    //         return response()->json(['message' => 'Work process not found'], 404);
    //     }
    // }

    public function update(Request $request, $workProcess)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'auto_ecole_id' => 'exists:auto_ecoles,id',
            'steps' => ['required', 'array']
        ]);
        if ($validator->fails()) {
            return Helper::handleValidationErrors($validator);
        }
        $workProcess = WorkProcess::find($workProcess);
        if (!$workProcess) {
            return Helper::handleNotFound("Wrok process not found:(");
        }
        try {
            $workProcess->update($validator->validate());
            return Helper::handleSuccessMessage("work process updated successfully");
        } catch (\Throwable $th) {
            return Helper::handleExceptions($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(WorkProcess $workProcess)
    // {
    //     try {
    //         $workProcess->delete();
    //         return response()->json(['message' => 'work process deleted successfully']);
    //     } catch (ModelNotFoundException $exception) {
    //         return response()->json(['message' => 'Work process not found'], 404);
    //     }
    // }
    public function destroy($workProcess)
    {
        $workProcess = WorkProcess::find($workProcess);
        if (!$workProcess) {
            return Helper::handleNotFound("Wrok process not found:(");
        }
        try {
            $workProcess->delete();
            return Helper::handleSuccessMessage("work process deleted successfully");
        } catch (\Throwable $th) {
            return Helper::handleExceptions($th);
        }
    }
}
