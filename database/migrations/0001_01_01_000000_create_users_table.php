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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->foreignId('role_id')->constrained('roles', 'role_id')->onDelete('cascade');
            $table->string('full_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone_number')->nullable();
            $table->string('institution')->nullable();
            $table->string('account_status')->default('Active');
            
            // Draw.io Observer specific fields
            $table->string('preferred_region', 100)->nullable();
            $table->string('saved_filters', 255)->nullable();
            $table->text('download_history')->nullable();

            // Draw.io Researcher specific fields
            $table->string('specialisation', 100)->nullable();
            $table->integer('upload_count')->default(0);
            $table->date('last_upload_date')->nullable();

            // Draw.io Admin specific fields
            $table->integer('admin_level')->default(1);
            $table->string('permissions', 255)->nullable();
            $table->timestamp('last_login')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
