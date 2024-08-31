<?php

namespace Database\Seeders;

use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = Carbon::now();

        Role::insert([
            ['name' => 'Admin', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Manager', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Staff', 'created_at' => $now, 'updated_at' => $now]
        ]);
    }
}
