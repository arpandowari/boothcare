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
        Schema::create('public_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booth_id')->constrained()->onDelete('cascade');
            $table->foreignId('family_member_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('reporter_name');
            $table->string('reporter_phone');
            $table->string('reporter_email')->nullable();
            $table->string('problem_title');
            $table->text('problem_description');
            $table->enum('category', ['infrastructure', 'sanitation', 'electricity', 'water', 'security', 'other']);
            $table->enum('priority', ['low', 'medium', 'high']);
            $table->enum('status', ['pending', 'verified', 'in_progress', 'resolved', 'rejected'])->default('pending');
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->text('admin_response')->nullable();
            $table->json('photos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_reports');
    }
};
