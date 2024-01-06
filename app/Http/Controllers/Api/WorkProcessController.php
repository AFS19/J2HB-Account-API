<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkProcessRequest;
use App\Http\Requests\UpdateWorkProcessRequest;
use App\Models\WorkProcess;

class WorkProcessController extends Controller
{
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
    public function store(StoreWorkProcessRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkProcess $workProcess)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkProcessRequest $request, WorkProcess $workProcess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkProcess $workProcess)
    {
        //
    }
}
