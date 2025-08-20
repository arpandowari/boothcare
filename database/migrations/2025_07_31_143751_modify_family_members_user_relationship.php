<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('family_members', function (Blueprint $table) {
            // Make user_id nullable (it might already be, but ensuring it)
            $table->foreignId('user_id')->nullable()->change();
            
            // Add a field to track if this member can log in
            $table->boolean('can_login')->default(false)->after('is_active');
            
            // Add a field to track when user account was created
            $table->timestamp('user_account_created_at')->nullable()->after('can_login');
        });
    }

    public function down(): void
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->dropColumn(['can_login', 'user_account_created_at']);
        });
    }
};