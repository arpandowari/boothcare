<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $booths = \App\Models\Booth::all();
        $sampleReviews = [
            ['rating' => 5, 'comment' => 'Excellent booth with great facilities and helpful staff.'],
            ['rating' => 4, 'comment' => 'Good overall experience, clean and well-maintained.'],
            ['rating' => 5, 'comment' => 'Very satisfied with the services provided here.'],
            ['rating' => 3, 'comment' => 'Average experience, could be improved.'],
            ['rating' => 4, 'comment' => 'Nice location and friendly community members.'],
            ['rating' => 5, 'comment' => 'Outstanding service and community support.'],
        ];
        
        $sampleNames = ['Rajesh Kumar', 'Priya Sharma', 'Amit Singh', 'Sunita Devi', 'Vikash Yadav', 'Meera Gupta'];
        
        foreach ($booths as $booth) {
            // Create 3-5 reviews for each booth
            for ($i = 0; $i < rand(3, 5); $i++) {
                $review = $sampleReviews[array_rand($sampleReviews)];
                \App\Models\Review::create([
                    'booth_id' => $booth->id,
                    'reviewer_name' => $sampleNames[array_rand($sampleNames)],
                    'reviewer_phone' => '9' . rand(100000000, 999999999),
                    'rating' => $review['rating'],
                    'comment' => $review['comment'],
                    'is_approved' => rand(0, 1) ? true : false, // Some approved, some pending
                    'approved_by' => rand(0, 1) ? 1 : null,
                    'approved_at' => rand(0, 1) ? now() : null,
                ]);
            }
        }
    }
}
