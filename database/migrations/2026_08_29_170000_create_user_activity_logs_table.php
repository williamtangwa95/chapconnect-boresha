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
        if (!Schema::hasTable('user_activity_logs')) {
            Schema::create('user_activity_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->string('action', 50); // LOGIN, LOGOUT, CREATED, UPDATED, DELETED, etc.
                $table->string('subject_type', 100)->nullable(); // User, Category, SystemSetting, etc.
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->text('description');
                $table->json('properties')->nullable(); // For old/new diff: {"old": {...}, "new": {...}}
                $table->string('ip_address', 45)->nullable();
                $table->string('user_agent', 1024)->nullable();
                $table->timestamps();

                $table->index('created_at');
                $table->index('user_id');
                $table->index(['subject_type', 'subject_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activity_logs');
    }
};
