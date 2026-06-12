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
        Schema::create('climate_data', function (Blueprint $table) {
            $table->id('climate_data_id');
            $table->foreignId('dataset_id')->constrained('datasets', 'dataset_id')->onDelete('cascade');
            $table->foreignId('region_id')->constrained('regions', 'region_id')->onDelete('cascade');
            $table->date('record_date');
            $table->decimal('temperature_celsius', 5, 2)->nullable();
            $table->decimal('rainfall_mm', 8, 2)->nullable();
            $table->decimal('humidity_percent', 5, 2)->nullable();
            $table->decimal('drought_index', 6, 2)->nullable();
            $table->string('flood_risk_level', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('climate_data');
    }
};
