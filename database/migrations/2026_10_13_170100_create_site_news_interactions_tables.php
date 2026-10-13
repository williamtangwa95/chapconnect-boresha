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
        // Site News Likes Table
        Schema::create('site_news_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_news_id')->constrained('site_news')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->string('device_fingerprint')->nullable();
            $table->timestamps();

            $table->index(['site_news_id', 'user_id']);
            $table->index(['site_news_id', 'ip_address', 'device_fingerprint']);
        });

        // Site News Shares Table
        Schema::create('site_news_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_news_id')->constrained('site_news')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('platform', 50)->default('general');
            $table->string('ip_address', 45)->nullable();
            $table->string('device_fingerprint')->nullable();
            $table->timestamps();

            $table->index(['site_news_id', 'platform']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_news_shares');
        Schema::dropIfExists('site_news_likes');
    }
};
