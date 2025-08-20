<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Booth;
use App\Models\House;
use App\Models\FamilyMember;
use App\Models\User;
use App\Models\Problem;

class QuickDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌟 Creating Quick Demo Data...');

        // Create Admin User if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@boothcare.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );
        $this->command->info("👤 Admin user ready: {$admin->email}");

        // Create Areas
        $areas = [
            [
                'area_name' => 'Central Mumbai',
                'district' => 'Mumbai',
                'division' => 'Maharashtra',
                'description' => 'Central business district of Mumbai',
                'is_active' => true,
            ],
            [
                'area_name' => 'South Delhi',
                'district' => 'New Delhi',
                'division' => 'Delhi',
                'description' => 'Upscale residential area in Delhi',
                'is_active' => true,
            ]
        ];

        foreach ($areas as $areaData) {
            $area = Area::create($areaData);
            $this->command->info("📍 Created area: {$area->area_name}");

            // Create 2 booths per area with unique booth numbers
            for ($b = 1; $b <= 2; $b++) {
                $globalBoothNumber = ($area->id - 1) * 2 + $b; // Make booth numbers unique globally
                $booth = Booth::create([
                    'area_id' => $area->id,
                    'booth_number' => str_pad($globalBoothNumber, 3, '0', STR_PAD_LEFT),
                    'booth_name' => $area->area_name . ' Booth ' . $b,
                    'description' => 'Polling booth in ' . $area->area_name,
                    'location' => $area->area_name,
                    'constituency' => $area->district . '-1',
                    'is_active' => true,
                ]);

                // Create 5 houses per booth
                for ($h = 1; $h <= 5; $h++) {
                    $houseNumber = 'H-' . str_pad(($b * 100) + $h, 3, '0', STR_PAD_LEFT);
                    
                    $house = House::create([
                        'booth_id' => $booth->id,
                        'house_number' => $houseNumber,
                        'address' => $houseNumber . ', Main Road, ' . $area->area_name,
                        'area' => $area->area_name,
                        'pincode' => $area->district === 'Mumbai' ? '400001' : '110001',
                        'latitude' => $area->district === 'Mumbai' ? 19.0760 : 28.7041,
                        'longitude' => $area->district === 'Mumbai' ? 72.8777 : 77.1025,
                        'is_active' => true,
                    ]);

                    // Create family members for each house
                    $this->createFamilyMembers($house);
                }
                
                $this->command->info("🏢 Created booth {$booth->booth_number} with 5 houses");
            }
        }

        $this->displayStats();
    }

    private function createFamilyMembers($house)
    {
        $surnames = ['Sharma', 'Patel', 'Singh', 'Kumar', 'Gupta'];
        $familySurname = $surnames[array_rand($surnames)];
        
        // Create family head
        $uniqueEmail = strtolower('head.' . $familySurname . '.' . $house->id . '@email.com');
        $head = FamilyMember::create([
            'house_id' => $house->id,
            'name' => 'Head ' . $familySurname,
            'date_of_birth' => '1980-01-01',
            'gender' => 'male',
            'relation_to_head' => 'head',
            'phone' => '+91 98765' . rand(10000, 99999),
            'email' => $uniqueEmail,
            'education' => 'Graduate',
            'occupation' => 'Business',
            'marital_status' => 'married',
            'aadhar_number' => rand(100000000000, 999999999999),
            'pan_number' => 'ABCDE' . rand(1000, 9999) . 'F',
            'voter_id' => 'VOTER' . rand(100000000, 999999999),
            'is_family_head' => true,
            'is_active' => true,
        ]);

        // Create user account for family head with unique email
        $uniqueEmail = strtolower('head.' . $familySurname . '.' . $house->id . '@email.com');
        User::create([
            'name' => $head->name,
            'email' => $uniqueEmail,
            'password' => bcrypt('password123'),
            'role' => 'user',
            'phone' => $head->phone,
            'date_of_birth' => $head->date_of_birth,
            'gender' => $head->gender,
            'nid_number' => $head->aadhar_number,
            'aadhar_number' => $head->aadhar_number,
            'is_active' => true,
        ]);

        // Create spouse
        FamilyMember::create([
            'house_id' => $house->id,
            'name' => 'Spouse ' . $familySurname,
            'date_of_birth' => '1985-01-01',
            'gender' => 'female',
            'relation_to_head' => 'spouse',
            'education' => 'Higher Secondary',
            'occupation' => 'Housewife',
            'marital_status' => 'married',
            'aadhar_number' => rand(100000000000, 999999999999),
            'voter_id' => 'VOTER' . rand(100000000, 999999999),
            'is_family_head' => false,
            'is_active' => true,
        ]);

        // Create child
        $child = FamilyMember::create([
            'house_id' => $house->id,
            'name' => 'Child ' . $familySurname,
            'date_of_birth' => '2010-01-01',
            'gender' => 'male',
            'relation_to_head' => 'son',
            'education' => 'Student',
            'occupation' => 'Student',
            'marital_status' => 'single',
            'is_family_head' => false,
            'is_active' => true,
        ]);

        // Create a problem for the head (50% chance)
        if (rand(0, 1)) {
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                Problem::create([
                    'family_member_id' => $head->id,
                    'reported_by' => $admin->id,
                    'title' => 'Employment Assistance Required',
                    'description' => 'Need help with job placement and skill development training.',
                    'category' => 'employment',
                    'priority' => 'medium',
                    'status' => 'reported',
                    'reported_date' => now()->subDays(rand(1, 30)),
                    'expected_resolution_date' => now()->addDays(rand(7, 30)),
                    'estimated_cost' => rand(5000, 15000),
                    'admin_notes' => 'Sample problem for demo purposes',
                    'is_public' => true,
                ]);
            }
        }
    }

    private function displayStats()
    {
        $this->command->info('');
        $this->command->info('🎉 Quick Demo Data Created!');
        $this->command->info('========================');
        $this->command->info('📊 Statistics:');
        $this->command->info('   Areas: ' . Area::count());
        $this->command->info('   Booths: ' . Booth::count());
        $this->command->info('   Houses: ' . House::count());
        $this->command->info('   Family Members: ' . FamilyMember::count());
        $this->command->info('   Problems: ' . Problem::count());
        $this->command->info('   Users: ' . User::count());
        $this->command->info('');
        $this->command->info('🔐 Login Credentials:');
        $this->command->info('   Admin: admin@boothcare.com / admin123');
        $this->command->info('   Users: head.sharma@email.com / password123');
        $this->command->info('');
        $this->command->info('✨ Your beautiful interfaces are now ready to explore!');
    }
}