<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('aadhar_number', 12)->unique()->nullable()->after('nid_number');
            $table->index('aadhar_number');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['aadhar_number']);
            $table->dropColumn('aadhar_number');
        });
    }
};