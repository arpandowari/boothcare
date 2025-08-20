<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BoothSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $booths = [
            [
                'booth_number' => '18',
                'booth_name' => 'Sherpur-18 Booth',
                'description' => 'Main booth for Sherpur constituency area 18',
                'location' => 'Sherpur District',
                'constituency' => 'Sherpur-1',
                'is_active' => true,
            ],
            [
                'booth_number' => '17',
                'booth_name' => 'Sherpur-17 Booth',
                'description' => 'Secondary booth for Sherpur constituency area 17',
                'location' => 'Sherpur District',
                'constituency' => 'Sherpur-1',
                'is_active' => true,
            ],
            [
                'booth_number' => '19',
                'booth_name' => 'Sherpur-19 Booth',
                'description' => 'Adjacent booth for Sherpur constituency area 19',
                'location' => 'Sherpur District',
                'constituency' => 'Sherpur-1',
                'is_active' => true,
            ]
        ];

        foreach ($booths as $booth) {
            \App\Models\Booth::create($booth);
        }

        // Create sample houses for booth 18
        $booth18 = \App\Models\Booth::where('booth_number', '18')->first();
        
        $houses = [
            [
                'booth_id' => $booth18->id,
                'house_number' => 'H-001',
                'address' => 'Main Road, Sherpur',
                'area' => 'Central Sherpur',
                'pincode' => '2100',
                'is_active' => true,
            ],
            [
                'booth_id' => $booth18->id,
                'house_number' => 'H-002',
                'address' => 'Station Road, Sherpur',
                'area' => 'Central Sherpur',
                'pincode' => '2100',
                'is_active' => true,
            ],
            [
                'booth_id' => $booth18->id,
                'house_number' => 'H-003',
                'address' => 'Market Street, Sherpur',
                'area' => 'Market Area',
                'pincode' => '2100',
                'is_active' => true,
            ]
        ];

        foreach ($houses as $house) {
            $houseModel = \App\Models\House::create($house);
            
            // Create a sample family for each house
            $family = \App\Models\Family::create([
                'house_id' => $houseModel->id,
                'family_head_name' => 'Family Head ' . $house['house_number'],
                'family_head_phone' => '01700000' . substr($house['house_number'], -3),
                'total_members' => rand(3, 7),
                'monthly_income' => rand(15000, 50000),
                'occupation' => ['Farmer', 'Teacher', 'Business', 'Service'][rand(0, 3)],
                'is_active' => true,
            ]);

            // Create sample family members
            $members = [
                [
                    'family_id' => $family->id,
                    'name' => 'Head of ' . $house['house_number'],
                    'date_of_birth' => '1980-01-01',
                    'gender' => 'male',
                    'relation_to_head' => 'self',
                    'phone' => '01700000' . substr($house['house_number'], -3),
                    'education' => 'Graduate',
                    'occupation' => $family->occupation,
                    'is_head' => true,
                    'is_active' => true,
                ],
                [
                    'family_id' => $family->id,
                    'name' => 'Spouse of ' . $house['house_number'],
                    'date_of_birth' => '1985-01-01',
                    'gender' => 'female',
                    'relation_to_head' => 'spouse',
                    'education' => 'Higher Secondary',
                    'occupation' => 'Housewife',
                    'is_head' => false,
                    'is_active' => true,
                ]
            ];

            foreach ($members as $member) {
                $memberModel = \App\Models\FamilyMember::create($member);
                
                // Create a sample problem for some members
                if (rand(0, 1)) {
                    \App\Models\Problem::create([
                        'family_member_id' => $memberModel->id,
                        'title' => 'Sample Problem for ' . $member['name'],
                        'description' => 'This is a sample problem description for testing purposes.',
                        'category' => ['health', 'education', 'employment', 'housing'][rand(0, 3)],
                        'priority' => ['low', 'medium', 'high'][rand(0, 2)],
                        'status' => 'reported',
                        'reported_date' => now()->subDays(rand(1, 30)),
                        'is_public' => true,
                    ]);
                }
            }
        }
    }
}
