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
        Schema::create('observation_reports', function (Blueprint $table) {
            $table->id('observation_id');
            
            // FK to user (Observer)
            $table->foreignId('public_id')->constrained('users', 'user_id')->onDelete('cascade');
            
            // FK to researcher (Reviewer)
            $table->foreignId('researcher_id')->nullable()->constrained('users', 'user_id')->onDelete('set null');
            
            // FK to flora (Registry mapping)
            $table->foreignId('flora_id')->nullable()->constrained('flora', 'flora_id')->onDelete('set null');
            
            $table->string('flora_name', 255);
            $table->string('location', 255);
            $table->text('description')->nullable();
            $table->string('image_path', 255)->nullable();
            
            $table->decimal('temperature_celsius', 5, 2)->nullable();
            $table->decimal('rainfall_mm', 6, 2)->nullable();
            $table->decimal('humidity_percent', 5, 2)->nullable();
            $table->decimal('drought_index', 4, 2)->nullable();
            $table->decimal('ndvi_value', 4, 3)->nullable();
            $table->decimal('vegetation_cover_percent', 5, 2)->nullable();
            $table->string('vegetation_condition', 50)->nullable();

            $table->date('date_observed');
            $table->dateTime('submission_date');
            
            $table->string('status', 50)->default('Pending'); // Pending, Approved, Rejected
            $table->text('review_comment')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observation_reports');
    }
};
