<?php

namespace Database\Seeders;

use App\Models\CourseUser;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourseUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CourseUser::insert([
            [
                'user_id' => 2,
                'course_id' => 1,
                'month' => now(),
                'watch_duration' => 20,
            ],
            [
                'user_id' => 2,
                'course_id' => 2,
                'month' => now(),
                'watch_duration' => 50,
            ],
            [
                'user_id' => 2,
                'course_id' => 3,
                'month' => now(),
                'watch_duration' => 30,
            ],
        ]);
    }
}
