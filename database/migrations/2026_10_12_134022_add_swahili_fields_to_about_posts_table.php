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
        Schema::table('about_posts', function (Blueprint $table) {
            $table->string('title_sw')->nullable()->after('title');
            $table->string('badge_sw')->nullable()->after('badge');
            $table->text('content_sw')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about_posts', function (Blueprint $table) {
            $table->dropColumn(['title_sw', 'badge_sw', 'content_sw']);
        });
    }
};
