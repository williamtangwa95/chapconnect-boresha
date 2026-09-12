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
        // Media Likes Table
        Schema::create('media_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained('media')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->string('device_fingerprint')->nullable();
            $table->timestamps();

            $table->index(['media_id', 'user_id']);
            $table->index(['media_id', 'ip_address', 'device_fingerprint']);
        });

        // Media Comments Table
        Schema::create('media_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained('media')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('media_comments')->onDelete('cascade');
            $table->string('author_name')->default('Guest Visitor');
            $table->text('comment');
            $table->string('ip_address', 45)->nullable();
            $table->string('device_fingerprint')->nullable();
            $table->timestamps();

            $table->index(['media_id', 'parent_id']);
        });

        // Media Shares Table
        Schema::create('media_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained('media')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('platform', 50)->default('general');
            $table->string('ip_address', 45)->nullable();
            $table->string('device_fingerprint')->nullable();
            $table->timestamps();

            $table->index(['media_id', 'platform']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_shares');
        Schema::dropIfExists('media_comments');
        Schema::dropIfExists('media_likes');
    }
};
