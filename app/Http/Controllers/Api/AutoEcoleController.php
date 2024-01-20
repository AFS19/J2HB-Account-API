<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAutoEcoleRequest;
use App\Http\Requests\UpdateAutoEcoleRequest;
use App\Http\Resources\AutoEcoleResource;
use App\Models\AutoEcole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class AutoEcoleController extends Controller
{
    public function __construct()
    {
        $this->middleware("permission:auto_ecoles-create")->only("store");
        $this->middleware("permission:auto_ecoles-read")->only("index", "show");
        $this->middleware("permission:auto_ecoles-update")->only("update");
        $this->middleware("permission:auto_ecoles-delete")->only("destroy");
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // validate token & auth user & user->autoEcoles
        $autoEcoles =  AutoEcole::paginate();
        return AutoEcoleResource::collection($autoEcoles);
    }

    /**
     * Display all specified user auto ecoles
     */
    public function monAutoEcoles()
    {
        $user = Auth()->user();
        try {
            $autoEcoles = AutoEcole::where("id", $user->id);
            return AutoEcoleResource::collection($autoEcoles);
        } catch (\Throwable $th) {
            return Helper::handleExceptions($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAutoEcoleRequest $request)
    {
        try {
            AutoEcole::create([
                'name' => $request->name,
                'gerant_id' => auth()->user()->id,
                'permis_list' => $request->permis_list,
            ]);
            return Helper::handleSuccessMessage("Auto ecole created successfully");
        } catch (\Throwable $th) {
            return Helper::handleExceptions($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AutoEcole $autoEcole)
    {
        try {
            $autoEcole = AutoEcole::find($autoEcole)->first();
            $user = auth()->user();
            if ($user->isAbleTo("auto_ecoles-read") && $autoEcole->gerant_id === $user->id) {
                return new AutoEcoleResource($autoEcole);
            } else {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        } catch (\Throwable $th) {
            return Helper::handleExceptions($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAutoEcoleRequest $request, AutoEcole $autoEcole)
    {
        try {
            $autoEcole->update([
                'name' => $request->name,
                'permis_list' => $request->permis_list,
            ]);
            return response()->json([
                'message' => 'Updated success',
                'data' => new AutoEcoleResource($autoEcole),
            ]);
        } catch (\Throwable $th) {
            return Helper::handleExceptions($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AutoEcole $autoEcole)
    {
        try {
            $autoEcole->delete();
            // return response()->json(['message' => 'Deleted Success'], 204);
            return Helper::handleSuccessMessage("Deleted Success");
        } catch (\Throwable $th) {
            return Helper::handleExceptions($th);
        }
    }
}
