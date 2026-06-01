<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            BarangSeeder::class,
        ]);

        // Owner
        $owner = User::firstOrCreate(
            ['username' => 'owner'],
            [
                'name' => 'Owner',
                'email' => 'owner@flopac.id',
                'password' => bcrypt('password'),
            ]
        );

        $owner->assignRole('Owner');

        // Admin
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin',
                'email' => 'admin@flopac.id',
                'password' => bcrypt('password'),
            ]
        );

        $admin->assignRole('Admin');
    }
}
