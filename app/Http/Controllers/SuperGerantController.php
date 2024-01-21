<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AutoEcole;
use Illuminate\Support\Facades\Validator;
use Laratrust\LaratrustFacade as Laratrust;

class SuperGerantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('supargerant');
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
        // Check if the authenticated user is SuperGerant
        $authUser = auth()->user();
        try {
            if ($authUser->hasRole('superGerant')) {
                // SuperGerant can only create candidate, moniteur, superGerant, and gerant
                if (!in_array($role, ['candidate', 'moniteur', 'superGerant', 'gerant'])) {
                    return response()->json(['error' => 'SuperGerant can only create candidate, moniteur, superGerant, and gerant'], 403);
                }
            }

            // Create user logic
            $user = new User;
            $user->setAttributes($validator->validated());
            $user->save();

            // Attach role
            $user->addRole($role);

            // Assign permissions based on role
            $permissions = $this->getPermissionsForRole($role);
            $user->givePermissions($permissions);

            // Create Auto Ecole for moniteur
            if ($role === 'moniteur') {
                $validator = Validator::make($request->all(), [
                    'name' => ['required', 'string', 'max:100', "unique:auto_ecoles"],
                    'gerant_id' => ['reqiored', 'exists:users,id'],
                    'permis_list' => ['required', 'array', 'in:AM,A1,A,B,C,D,EB,EC,ED'],
                ]);
                if ($validator->fails()) {
                    return Helper::handleValidationErrors($validator);
                }
                $autoEcole = new AutoEcole($validator->validate());
                $autoEcole->save();

                // Assign Auto Ecole to the user
                $user->autoEcole()->associate($autoEcole);
                $user->save();
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
            case 'superGerant':
                return ['users_c', 'users_r', 'users_u', 'users_d', 'auto_ecoles_c', 'auto_ecoles_r', 'auto_ecoles_u', 'work_process_c', 'work_process_r', 'work_process_u', 'work_process_d'];
            case 'gerant':
                return ['auto_ecoles_c', 'auto_ecoles_r', 'auto_ecoles_u', 'work_process_c', 'work_process_r', 'work_process_u', 'work_process_d'];
            case 'moniteur':
                return ['profile_r', 'profile_u', 'auto_ecoles_r'];
            case 'candidate':
                return ['auto_ecoles_r', 'work_process_r'];
                // Add more cases as needed
            default:
                return [];
        }
    }
}
