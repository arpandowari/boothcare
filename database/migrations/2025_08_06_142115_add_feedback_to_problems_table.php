<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('problems', function (Blueprint $table) {
            $table->text('user_feedback')->nullable()->after('admin_notes');
            $table->integer('user_rating')->nullable()->after('user_feedback');
            $table->timestamp('feedback_date')->nullable()->after('user_rating');
            $table->boolean('feedback_submitted')->default(false)->after('feedback_date');
        });
    }

    public function down(): void
    {
        Schema::table('problems', function (Blueprint $table) {
            $table->dropColumn(['user_feedback', 'user_rating', 'feedback_date', 'feedback_submitted']);
        });
    }
};