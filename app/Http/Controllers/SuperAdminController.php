<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AutoEcole;
use Illuminate\Support\Facades\Validator;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('superAdmin');
    }

    public function createUser(Request $request, $role)
    {
        // Validation logic here
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'between:2,100'],
            'phone' => ['required', 'string', 'min:10', 'regex:/^(06|07)\d{8}$/'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:6'],
        ]);
        if ($validator->fails()) {
            return Helper::handleValidationErrors($validator);
        }
        try {
            // Create user logic
            $user = new User;
            $user->setAttributes($validator->validated());
            $user->save();
            // Attach role
            $user->addRole($role);

            // Assign permissions based on role
            $permissions = $this->getPermissionsForRole($role);
            $user->givePermissions($permissions);

            // Create Auto Ecole for superGerant
            if ($role === 'superGerant') {
                // Merge gerant_id into the request data
                $request->merge(['gerant_id' => auth()->user()->id]);
                $validator = Validator::make($request->all(), [
                    'name' => ['required', 'string', 'max:100', "unique:auto_ecoles"],
                    'gerant_id' => ['exists:users,id'],
                    'permis_list' => ['required', 'array', 'in:AM,A1,A,B,C,D,EB,EC,ED'],
                ]);
                // Check for validation failure
                if ($validator->fails()) {
                    // If validation fails, return validation errors
                    return Helper::handleValidationErrors($validator);
                }



                $autoEcole = AutoEcole::create($validator->validate());
                // $autoEcole->save();

                // Assign Auto Ecole to the user
                // $user->autoEcole()->associate($autoEcole);
                // $user->save();
            }

            return response()->json(['message' => ucfirst($role) . ' created successfully', 'data' => $user], 201);
        } catch (\Throwable $th) {
            return Helper::handleExceptions($th);
        }
    }

    public function handleUsers($role)
    {
        // Handle users logic based on role
        $users = User::whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })->get();
        return response()->json(['data' => $users]);
    }

    // Add other methods as needed

    private function getPermissionsForRole($role)
    {
        // Define permissions based on role
        switch ($role) {
            case 'superAdmin':
                return [
                    "users-create",
                    "users-read",
                    "users-update",
                    "users-delete",

                    "payments-create",
                    "payments-read",
                    "payments-update",
                    "payments-delete",

                    "auto_ecoles-create",
                    "auto_ecoles-read",
                    "auto_ecoles-update",
                    "auto_ecoles-delete",
                ];
            case 'admin':
                return [
                    "payments-create",
                    "payments-read",
                    "payments-update",
                    "payments-delete",

                    "auto_ecoles-create",
                    "auto_ecoles-read",
                    "auto_ecoles-update",
                    "auto_ecoles-delete",
                ];
            case 'superGerant':
                return [
                    "users-create",
                    "users-read",
                    "users-update",
                    "users-delete",

                    "auto_ecoles-create",
                    "auto_ecoles-read",
                    "auto_ecoles-update",

                    "work_process-create",
                    "work_process-read",
                    "work_process-update",
                    "work_process-delete",

                ];
            case 'gerant':
                return [
                    "auto_ecoles-create",
                    "auto_ecoles-read",
                    "auto_ecoles-update",

                    "work_process-create",
                    "work_process-read",
                    "work_process-update",
                    "work_process-delete",

                ];
            case 'moniteur':
                return [
                    'profile_read',
                    'profile_update',

                    'auto_ecoles_read'
                ];
            case 'candidate':
                return [
                    'auto_ecoles_read',
                    'work_process_read'
                ];
            default:
                return [];
        }
    }
}
