<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BoothImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $booths = \App\Models\Booth::all();
        
        foreach ($booths as $booth) {
            // Create 2-3 sample images for each booth
            for ($i = 1; $i <= rand(2, 3); $i++) {
                \App\Models\BoothImage::create([
                    'booth_id' => $booth->id,
                    'image_path' => 'booth_images/sample_booth_' . $i . '.jpg',
                    'title' => 'Booth View ' . $i,
                    'description' => 'Sample image ' . $i . ' for ' . $booth->booth_name,
                    'is_active' => true,
                    'sort_order' => $i
                ]);
            }
        }
    }
}
