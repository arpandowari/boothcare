<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('update_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('request_type'); // 'profile', 'family_member', 'documents'
            $table->foreignId('target_id')->nullable(); // ID of the record being updated (family_member_id, etc.)
            $table->string('target_type')->nullable(); // Model type being updated
            $table->json('current_data'); // Current data before update
            $table->json('requested_data'); // Requested changes
            $table->text('reason')->nullable(); // Reason for the update
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('review_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('update_requests');
    }
};