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
        // Add user roles and profile fields
        Schema::table('users', function (Blueprint $table) {
            // Only add role column if it doesn't exist
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'user'])->default('user')->after('email');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('users', 'nid_number')) {
                $table->string('nid_number')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('nid_number');
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('profile_photo');
            }
        });

        // Add area_id to booths table
        Schema::table('booths', function (Blueprint $table) {
            $table->foreignId('area_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        // First, modify family_members to be directly under houses
        Schema::table('family_members', function (Blueprint $table) {
            // Drop foreign key constraint first if it exists
            if (Schema::hasColumn('family_members', 'family_id')) {
                $table->dropForeign(['family_id']);
            }
            
            // Add new columns only if they don't exist
            if (!Schema::hasColumn('family_members', 'house_id')) {
                $table->foreignId('house_id')->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('family_members', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('house_id')->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('family_members', 'is_family_head')) {
                $table->boolean('is_family_head')->default(false)->after('is_head');
            }
            if (!Schema::hasColumn('family_members', 'relationship_to_head')) {
                $table->string('relationship_to_head')->nullable()->after('relation_to_head');
            }
            if (!Schema::hasColumn('family_members', 'monthly_income')) {
                $table->decimal('monthly_income', 10, 2)->nullable()->after('occupation');
            }
        });

        // Now drop the family_id column if it exists
        Schema::table('family_members', function (Blueprint $table) {
            if (Schema::hasColumn('family_members', 'family_id')) {
                $table->dropColumn('family_id');
            }
        });

        // Remove families table after modifying family_members
        Schema::dropIfExists('families');

        // Modify problems table
        Schema::table('problems', function (Blueprint $table) {
            $table->foreignId('reported_by')->nullable()->after('family_member_id')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
