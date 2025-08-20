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
        Schema::create('page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'hero_title', 'hero_description', 'slider_images'
            $table->text('value'); // JSON or text value
            $table->string('type')->default('text'); // text, json, image, boolean
            $table->string('section')->default('general'); // hero, notices, features, etc.
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_settings');
    }
};
