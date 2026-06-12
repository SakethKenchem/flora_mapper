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
        Schema::create('vegetation_data', function (Blueprint $table) {
            $table->id('vegetation_data_id');
            $table->foreignId('dataset_id')->constrained('datasets', 'dataset_id')->onDelete('cascade');
            $table->foreignId('region_id')->constrained('regions', 'region_id')->onDelete('cascade');
            $table->date('record_date');
            $table->decimal('ndvi_value', 5, 3);
            $table->decimal('vegetation_cover_percent', 5, 2)->nullable();
            $table->string('vegetation_condition', 50)->nullable();
            $table->string('data_source', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vegetation_data');
    }
};
