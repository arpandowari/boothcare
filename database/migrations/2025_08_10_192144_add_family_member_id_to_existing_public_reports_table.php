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
        Schema::table('public_reports', function (Blueprint $table) {
            $table->foreignId('family_member_id')->nullable()->after('booth_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_reports', function (Blueprint $table) {
            $table->dropForeign(['family_member_id']);
            $table->dropColumn('family_member_id');
        });
    }
};
