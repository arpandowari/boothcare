<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NoticeAndPageSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default notices
        \App\Models\Notice::create([
            'title' => 'Welcome to BoothCare',
            'content' => 'New member registration is now open for all areas. Document verification process has been simplified.',
            'type' => 'important',
            'display_location' => 'marquee',
            'is_active' => true,
            'priority' => 10,
            'author' => 'Admin Team',
            'created_by' => 1
        ]);

        \App\Models\Notice::create([
            'title' => 'System Maintenance Notice',
            'content' => 'Scheduled maintenance on Sunday, 2:00 AM - 4:00 AM. Services may be temporarily unavailable during this period.',
            'type' => 'urgent',
            'display_location' => 'card',
            'is_active' => true,
            'priority' => 20,
            'author' => 'Technical Team',
            'created_by' => 1
        ]);

        \App\Models\Notice::create([
            'title' => 'New Document Verification Process',
            'content' => 'We have simplified the document verification process. Now you can upload documents directly through the portal.',
            'type' => 'important',
            'display_location' => 'card',
            'is_active' => true,
            'priority' => 15,
            'author' => 'Verification Team',
            'created_by' => 1
        ]);

        \App\Models\Notice::create([
            'title' => 'Community Feedback System Live',
            'content' => 'Share your experience and help us improve our services. Your feedback is valuable to us and the community.',
            'type' => 'general',
            'display_location' => 'card',
            'is_active' => true,
            'priority' => 5,
            'author' => 'Community Manager',
            'created_by' => 1
        ]);

        // Create default page settings
        \App\Models\PageSetting::create([
            'key' => 'hero_title',
            'value' => 'BoothCare Management',
            'type' => 'text',
            'section' => 'hero',
            'description' => 'Main title displayed on the hero section',
            'updated_by' => 1
        ]);

        \App\Models\PageSetting::create([
            'key' => 'hero_subtitle',
            'value' => 'India\'s most trusted community management platform with government-grade security, Aadhar verification, and comprehensive booth administration services.',
            'type' => 'text',
            'section' => 'hero',
            'description' => 'Subtitle/description displayed on the hero section',
            'updated_by' => 1
        ]);

        \App\Models\PageSetting::create([
            'key' => 'slider_images',
            'value' => json_encode([
                [
                    'url' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                    'title' => 'BoothCare Management',
                    'description' => 'India\'s most trusted community management platform with government-grade security, Aadhar verification, and comprehensive booth administration services.',
                    'badge' => '🏛️ Government Approved Platform'
                ],
                [
                    'url' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                    'title' => 'Smart Community Solutions',
                    'description' => 'Streamline your community operations with AI-powered analytics, real-time reporting, and automated member management systems.',
                    'badge' => '🚀 Advanced Technology'
                ],
                [
                    'url' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                    'title' => 'Secure & Compliant',
                    'description' => 'Your data is protected with military-grade encryption, Aadhar verification, and compliance with all government data protection regulations.',
                    'badge' => '🔒 Bank-Level Security'
                ]
            ]),
            'type' => 'json',
            'section' => 'hero',
            'description' => 'Slider images and content for the hero section',
            'updated_by' => 1
        ]);

        \App\Models\PageSetting::create([
            'key' => 'notices_enabled',
            'value' => '1',
            'type' => 'boolean',
            'section' => 'notices',
            'description' => 'Enable/disable the notices section',
            'updated_by' => 1
        ]);

        \App\Models\PageSetting::create([
            'key' => 'marquee_enabled',
            'value' => '1',
            'type' => 'boolean',
            'section' => 'notices',
            'description' => 'Enable/disable the marquee notice board',
            'updated_by' => 1
        ]);
    }
}
