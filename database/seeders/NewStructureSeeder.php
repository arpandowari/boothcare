<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@boothcare.in',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'phone' => '01700000001',
            'date_of_birth' => '1985-01-01',
            'gender' => 'male',
            'nid_number' => 'ADMIN123456789',
            'aadhar_number' => null, // Admin doesn't need aadhar
            'is_active' => true,
        ]);

        // Create Areas
        $areas = [
            [
                'area_name' => 'Sherpur Sadar',
                'district' => 'Sherpur',
                'division' => 'Mymensingh',
                'description' => 'Central area of Sherpur district',
                'is_active' => true,
            ],
            [
                'area_name' => 'Nalitabari',
                'district' => 'Sherpur',
                'division' => 'Mymensingh',
                'description' => 'Nalitabari upazila area',
                'is_active' => true,
            ]
        ];

        foreach ($areas as $areaData) {
            $area = \App\Models\Area::create($areaData);
            
            // Create Booths for each area
            $booths = [
                [
                    'area_id' => $area->id,
                    'booth_number' => $area->area_name === 'Sherpur Sadar' ? '18' : '17',
                    'booth_name' => 'Sherpur-' . ($area->area_name === 'Sherpur Sadar' ? '18' : '17') . ' Booth',
                    'description' => 'Voting booth in ' . $area->area_name,
                    'location' => $area->area_name,
                    'constituency' => 'Sherpur-1',
                    'is_active' => true,
                ],
                [
                    'area_id' => $area->id,
                    'booth_number' => $area->area_name === 'Sherpur Sadar' ? '19' : '20',
                    'booth_name' => 'Sherpur-' . ($area->area_name === 'Sherpur Sadar' ? '19' : '20') . ' Booth',
                    'description' => 'Secondary voting booth in ' . $area->area_name,
                    'location' => $area->area_name,
                    'constituency' => 'Sherpur-1',
                    'is_active' => true,
                ]
            ];

            foreach ($booths as $boothData) {
                $booth = \App\Models\Booth::create($boothData);
                
                // Create Houses for each booth
                for ($h = 1; $h <= 3; $h++) {
                    $house = \App\Models\House::create([
                        'booth_id' => $booth->id,
                        'house_number' => 'H-' . str_pad($h, 3, '0', STR_PAD_LEFT),
                        'address' => 'House ' . $h . ', ' . $booth->location . ', Sherpur',
                        'area' => $booth->location,
                        'pincode' => '2100',
                        'latitude' => 25.0000 + ($h * 0.001),
                        'longitude' => 90.0000 + ($h * 0.001),
                        'is_active' => true,
                    ]);

                    // Create Family Members directly under houses
                    $familyMembers = [
                        [
                            'name' => 'Family Head ' . $booth->booth_number . '-' . $house->house_number,
                            'date_of_birth' => '1980-01-01',
                            'gender' => 'male',
                            'relation_to_head' => 'self',
                            'relationship_to_head' => 'Head of Family',
                            'phone' => '017' . $booth->booth_number . str_pad($h, 6, '0', STR_PAD_LEFT),
                            'email' => 'family' . $booth->booth_number . $h . '@example.com',
                            'education' => 'Graduate',
                            'occupation' => ['Farmer', 'Teacher', 'Business', 'Service'][rand(0, 3)],
                            'marital_status' => 'married',
                            'monthly_income' => rand(15000, 50000),
                            'aadhar_number' => '1234' . str_pad($booth->booth_number . $h, 8, '0', STR_PAD_LEFT),
                            'pan_number' => 'ABCDE' . $booth->booth_number . str_pad($h, 3, '0', STR_PAD_LEFT) . 'F',
                            'voter_id' => 'VOTER' . $booth->booth_number . str_pad($h, 6, '0', STR_PAD_LEFT),
                            'ration_card_number' => 'RATION' . $booth->booth_number . str_pad($h, 5, '0', STR_PAD_LEFT),
                            'is_head' => true,
                            'is_family_head' => true,
                            'is_active' => true,
                        ],
                        [
                            'name' => 'Spouse ' . $booth->booth_number . '-' . $house->house_number,
                            'date_of_birth' => '1985-01-01',
                            'gender' => 'female',
                            'relation_to_head' => 'spouse',
                            'relationship_to_head' => 'Wife',
                            'education' => 'Higher Secondary',
                            'occupation' => 'Housewife',
                            'marital_status' => 'married',
                            'aadhar_number' => '5678' . str_pad($booth->booth_number . $h, 8, '0', STR_PAD_LEFT),
                            'voter_id' => 'VOTER' . $booth->booth_number . str_pad($h + 100, 6, '0', STR_PAD_LEFT),
                            'is_head' => false,
                            'is_family_head' => false,
                            'is_active' => true,
                        ],
                        [
                            'name' => 'Child ' . $booth->booth_number . '-' . $house->house_number,
                            'date_of_birth' => '2010-01-01',
                            'gender' => ['male', 'female'][rand(0, 1)],
                            'relation_to_head' => 'child',
                            'relationship_to_head' => 'Son/Daughter',
                            'education' => 'Student',
                            'occupation' => 'Student',
                            'marital_status' => 'unmarried',
                            'is_head' => false,
                            'is_family_head' => false,
                            'is_active' => true,
                        ]
                    ];

                    foreach ($familyMembers as $memberData) {
                        // Create user account for family head
                        $user = null;
                        if ($memberData['is_family_head']) {
                            $user = \App\Models\User::create([
                                'name' => $memberData['name'],
                                'email' => $memberData['email'],
                                'password' => bcrypt($memberData['date_of_birth']), // Password is date of birth
                                'role' => 'user',
                                'phone' => $memberData['phone'],
                                'date_of_birth' => $memberData['date_of_birth'],
                                'gender' => $memberData['gender'],
                                'nid_number' => $memberData['aadhar_number'],
                                'aadhar_number' => $memberData['aadhar_number'],
                                'is_active' => true,
                            ]);
                        }

                        $memberData['house_id'] = $house->id;
                        $memberData['user_id'] = $user?->id;
                        
                        $member = \App\Models\FamilyMember::create($memberData);
                        
                        // Create sample problems for some members
                        if (rand(0, 2) === 0) { // 33% chance of having a problem
                            \App\Models\Problem::create([
                                'family_member_id' => $member->id,
                                'reported_by' => $user?->id ?? $admin->id,
                                'title' => 'Sample Problem for ' . $member->name,
                                'description' => 'This is a sample problem description for testing the new structure. The issue is related to ' . ['health', 'education', 'employment', 'housing'][rand(0, 3)] . ' concerns.',
                                'category' => ['health', 'education', 'employment', 'housing', 'legal', 'financial', 'social', 'infrastructure'][rand(0, 7)],
                                'priority' => ['low', 'medium', 'high', 'urgent'][rand(0, 3)],
                                'status' => ['reported', 'in_progress', 'resolved'][rand(0, 2)],
                                'reported_date' => now()->subDays(rand(1, 30)),
                                'expected_resolution_date' => now()->addDays(rand(7, 30)),
                                'estimated_cost' => rand(1000, 10000),
                                'admin_notes' => 'Sample problem created via new structure seeder',
                                'is_public' => true,
                            ]);
                        }
                    }
                }
            }
        }

        $this->command->info('New structure data seeded successfully!');
        $this->command->info('Areas: ' . \App\Models\Area::count());
        $this->command->info('Booths: ' . \App\Models\Booth::count());
        $this->command->info('Houses: ' . \App\Models\House::count());
        $this->command->info('Family Members: ' . \App\Models\FamilyMember::count());
        $this->command->info('Problems: ' . \App\Models\Problem::count());
        $this->command->info('Users: ' . \App\Models\User::count());
        $this->command->info('');
        $this->command->info('Admin Login: admin@boothcare.in / admin123');
        $this->command->info('User Login: Use Aadhar number (e.g., 123400000181) / Date of Birth (1980-01-01)');
    }
}
