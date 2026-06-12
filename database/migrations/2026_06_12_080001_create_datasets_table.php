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
        Schema::create('datasets', function (Blueprint $table) {
            $table->id('dataset_id');
            $table->foreignId('uploaded_by')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('dataset_name', 150);
            $table->string('dataset_type', 50); // Climate or Vegetation
            $table->string('source_name', 150)->nullable();
            $table->string('file_name', 255)->nullable();
            $table->text('file_path')->nullable();
            $table->text('description')->nullable();
            $table->string('upload_status', 30)->default('Pending'); // Pending, Validated, Rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datasets');
    }
};
