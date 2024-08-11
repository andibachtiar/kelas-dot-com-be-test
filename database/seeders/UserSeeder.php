<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'mentor',
            'email' => 'mentor@example.com',
            'role' => 'mentor',
        ]);

        User::factory()->create([
            'name' => 'murid',
            'email' => 'murid@example.com',
            'role' => 'murid',
            'subscription_id' => 1,
            'subscription_start' => now(),
            'subscription_end' => now()->addMonth(),
        ]);
    }
}
