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
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('type')->default('customer');
            $table->string('country_code')->nullable();
            $table->string('country_name')->nullable();
            $table->string('phone')->nullable();
            $table->tinyInteger('is_active')->default(1)->nullable();
            $table->string('verification_code')->nullable();
            $table->enum('newsletter_frequency', ['never','daily','weekly','monthly','quaterly','yearly'])->default('never');
            $table->enum('lunch_reminder_frequency', ['never','daily','weekly','monthly','quaterly','yearly'])->default('never');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('facebook_id')->nullable();
            $table->string('google_id')->nullable();
            $table->rememberToken();
            $table->unsignedInteger('created_at')->nullable();
            $table->unsignedInteger('updated_at')->nullable();
            $table->unsignedInteger('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
