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
        // Check if role column exists, if not, create it first
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['admin', 'user'])->default('user')->after('email');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            // Add permissions column as JSON to store custom permissions
            if (!Schema::hasColumn('users', 'permissions')) {
                $table->json('permissions')->nullable()->after('role');
            }
            
            // Update role column to include sub-admin if it exists
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
        
        // Re-add role column with sub-admin option
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'sub_admin', 'user'])->default('user')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'permissions')) {
                $table->dropColumn('permissions');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
        
        // Restore original role column
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user'])->default('user')->after('email');
        });
    }
};