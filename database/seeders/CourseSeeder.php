<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::insert([
            [
                "name" => "Kelas AA",
                "video_duration" => 120,
                "mentor_id" => 1,
            ],
            [
                "name" => "Kelas BB",
                "video_duration" => 60,
                "mentor_id" => 1,
            ],
            [
                "name" => "Kelas CC",
                "video_duration" => 45,
                "mentor_id" => 1,
            ]
        ]);
    }
}
