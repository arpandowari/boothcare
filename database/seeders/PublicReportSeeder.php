<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublicReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $booths = \App\Models\Booth::all();
        $sampleReports = [
            [
                'title' => 'Street Light Not Working',
                'description' => 'The street light near the booth entrance has been out for several days, making it difficult to navigate in the evening.',
                'category' => 'electricity',
                'priority' => 'medium'
            ],
            [
                'title' => 'Water Logging Issue',
                'description' => 'During monsoon, water accumulates near the booth area causing inconvenience to residents.',
                'category' => 'water',
                'priority' => 'high'
            ],
            [
                'title' => 'Garbage Collection Delay',
                'description' => 'Garbage collection has been irregular in this area, leading to hygiene issues.',
                'category' => 'sanitation',
                'priority' => 'medium'
            ],
            [
                'title' => 'Road Repair Needed',
                'description' => 'The road leading to the booth has several potholes that need immediate attention.',
                'category' => 'infrastructure',
                'priority' => 'high'
            ],
            [
                'title' => 'Security Concern',
                'description' => 'Inadequate lighting and security measures in the evening hours.',
                'category' => 'security',
                'priority' => 'high'
            ],
        ];
        
        $sampleNames = ['Ramesh Gupta', 'Sita Devi', 'Mohan Lal', 'Kavita Singh', 'Suresh Kumar', 'Anita Sharma'];
        $statuses = ['pending', 'verified', 'in_progress', 'resolved'];
        
        foreach ($booths as $booth) {
            // Create 2-4 reports for each booth
            for ($i = 0; $i < rand(2, 4); $i++) {
                $report = $sampleReports[array_rand($sampleReports)];
                $status = $statuses[array_rand($statuses)];
                
                \App\Models\PublicReport::create([
                    'booth_id' => $booth->id,
                    'reporter_name' => $sampleNames[array_rand($sampleNames)],
                    'reporter_phone' => '9' . rand(100000000, 999999999),
                    'reporter_email' => strtolower(str_replace(' ', '.', $sampleNames[array_rand($sampleNames)])) . '@example.com',
                    'problem_title' => $report['title'],
                    'problem_description' => $report['description'],
                    'category' => $report['category'],
                    'priority' => $report['priority'],
                    'status' => $status,
                    'is_verified' => $status !== 'pending',
                    'verified_by' => $status !== 'pending' ? 1 : null,
                    'verified_at' => $status !== 'pending' ? now()->subDays(rand(1, 10)) : null,
                    'admin_response' => $status === 'resolved' ? 'Issue has been resolved successfully.' : ($status === 'in_progress' ? 'We are working on this issue.' : null),
                ]);
            }
        }
    }
}
