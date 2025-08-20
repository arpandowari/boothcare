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
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('relation_to_head');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('education')->nullable();
            $table->string('occupation')->nullable();
            $table->string('marital_status')->nullable();
            
            // Government ID Documents
            $table->string('aadhar_number')->nullable();
            $table->string('aadhar_photo')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('pan_photo')->nullable();
            $table->string('voter_id')->nullable();
            $table->string('voter_id_photo')->nullable();
            $table->string('ration_card_number')->nullable();
            $table->string('ration_card_photo')->nullable();
            
            // Personal Photo
            $table->string('profile_photo')->nullable();
            
            // Additional Info
            $table->text('medical_conditions')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_head')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};
