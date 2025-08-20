<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use App\Services\EmailService;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index(): View
    {
        // Get current settings from cache or default values
        $settings = [
            'app_name' => config('app.name', 'Boothcare'),
            'app_description' => Cache::get('app_description', 'Booth-wise citizen data management system'),
            'contact_email' => Cache::get('contact_email', 'admin@boothcare.in'),
            'contact_phone' => Cache::get('contact_phone', '+91-9876543210'),
            'office_address' => Cache::get('office_address', 'Sherpur, Mymensingh, Bangladesh'),
            'enable_notifications' => Cache::get('enable_notifications', true),
            'enable_problem_tracking' => Cache::get('enable_problem_tracking', true),
            'max_file_size' => Cache::get('max_file_size', 2048), // KB
        ];

        return view('settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_description' => 'required|string|max:500',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'office_address' => 'required|string|max:500',
            'enable_notifications' => 'boolean',
            'enable_problem_tracking' => 'boolean',
            'max_file_size' => 'required|integer|min:512|max:10240',
        ]);

        // Store settings in cache
        foreach ($validated as $key => $value) {
            Cache::put($key, $value, now()->addYears(1));
        }

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully!');
    }

    /**
     * Test email configuration
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email'
        ]);

        $emailService = new EmailService();
        $result = $emailService->testEmailConfiguration($request->test_email);

        if ($result) {
            return redirect()->route('settings.index')->with('success', 'Test email sent successfully to ' . $request->test_email . '!');
        } else {
            return redirect()->route('settings.index')->with('error', 'Failed to send test email. Please check your email configuration.');
        }
    }
}