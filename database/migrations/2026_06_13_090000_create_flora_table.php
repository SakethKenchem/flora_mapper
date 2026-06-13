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
        Schema::create('flora', function (Blueprint $table) {
            $table->id('flora_id');
            $table->foreignId('dataset_id')->nullable()->constrained('datasets', 'dataset_id')->onDelete('set null');
            $table->foreignId('region_id')->nullable()->constrained('regions', 'region_id')->onDelete('cascade');
            $table->string('scientific_name', 150)->unique();
            $table->string('common_name', 150)->nullable();
            $table->string('species_type', 100)->nullable();
            $table->string('conservation_status', 50)->nullable();
            $table->string('habitat_type', 100)->nullable();
            $table->string('vulnerability_level', 50)->default('Low'); // Low, Moderate, High
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flora');
    }
};
