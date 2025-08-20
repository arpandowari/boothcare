<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@boothcare.in'],
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@boothcare.in',
                'password' => bcrypt('superadmin123'),
                'role' => 'super_admin',
                'phone' => '01700000000',
                'date_of_birth' => '1980-01-01',
                'gender' => 'male',
                'nid_number' => 'SUPER123456789',
                'aadhar_number' => null,
                'is_active' => true,
            ]
        );

        // Update existing admin to regular admin
        User::where('email', 'admin@boothcare.in')->update([
            'role' => 'admin'
        ]);

        $this->command->info('✅ Super Admin created: superadmin@boothcare.in / superadmin123');
        $this->command->info('✅ Existing admin updated to regular admin role');
        
        $this->command->info('');
        $this->command->info('🔐 User Hierarchy:');
        $this->command->info('   Super Admin: Can directly edit all data');
        $this->command->info('   Admin: Can only request changes and view data');
        $this->command->info('   Family Members: Can only request changes to their data and view');
    }
}