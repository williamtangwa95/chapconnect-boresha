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
        Schema::create('chap_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('tutorial'); // tutorial, news, announcement, testimony
            $table->string('media_type')->default('article'); // article, photo, video, audio
            $table->string('featured_image')->nullable();
            $table->string('audio_path')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_path')->nullable();
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['category', 'is_published']);
            $table->index(['media_type', 'is_published']);
            $table->index('is_pinned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chap_posts');
    }
};
