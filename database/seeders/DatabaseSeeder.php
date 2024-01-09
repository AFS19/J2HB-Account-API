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

        # admin user
        $admin = \App\Models\User::factory()->create([
            'name' => 'admin',
            'phone' => '0600000001',
            'email' => 'admin@j2hb.com',
            'password' => 'j2hb2024'
        ]);
        $admin->addRole('admin');

        # gerant user
        $gerant = \App\Models\User::factory()->create([
            'name' => 'gerant',
            'phone' => '0600000002',
            'email' => 'gerant@j2hb.com',
            'password' => 'j2hb2024'
        ]);
        $gerant->addRole('gerant');
    }
}
