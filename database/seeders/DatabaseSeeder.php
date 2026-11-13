<?php

namespace Database\Seeders;

use App\Models\categories;
use App\Models\currencies;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // currencies::factory()->count(3)->create();
        categories::factory()->count(4)->create();
    }
}
