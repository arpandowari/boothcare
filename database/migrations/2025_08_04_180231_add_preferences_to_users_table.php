<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('preferences')->nullable()->after('remember_token');
            $table->string('profile_visibility')->default('members')->after('preferences');
            $table->boolean('show_contact')->default(true)->after('profile_visibility');
            $table->boolean('show_activity')->default(false)->after('show_contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['preferences', 'profile_visibility', 'show_contact', 'show_activity']);
        });
    }
};
