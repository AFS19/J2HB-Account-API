<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        # seed roles
        $this->call(LaratrustSeeder::class);

        # super admin user
        $superAdmin = \App\Models\User::factory()->create([
            'name' => 'super admin',
            'phone' => '0600000001',
            'email' => 'admin@j2hb.com',
            'password' => 'j2hb2024'
        ]);
        $superAdmin->addRole('superAdmin');
        // $admin->givePermissions([
        //     "users-create", "users-red", "users-update", "users-delete",
        //     "payments-create", "payments-red", "payments-update", "payments-delete",
        //     "auto_ecoles-create", "auto_ecoles-red", "auto_ecoles-update", "auto_ecoles-delete",
        // ]);

        # gerant user
        $superGerant = \App\Models\User::factory()->create([
            'name' => 'super gerant',
            'phone' => '0600000002',
            'email' => 'gerant@j2hb.com',
            'password' => 'j2hb2024'
        ]);
        $superGerant->addRole('superGerant');
        // $superGerent->givePermissions([
        //     "users-create", "users-red", "users-update", "users-delete",
        //     "auto_ecoles-create", "auto_ecoles-red", "auto_ecoles-update",
        //     "work_process-create", "work_process-red", "work_process-update", "work_process-delete",
        // ]);
    }
}
