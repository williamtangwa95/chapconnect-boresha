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
        // 1. Add views_count to users table if not exists
        if (!Schema::hasColumn('users', 'views_count')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('views_count')->default(0);
            });
        }

        // 2. Add device_fingerprint to likes table if not exists
        if (!Schema::hasColumn('likes', 'device_fingerprint')) {
            Schema::table('likes', function (Blueprint $table) {
                $table->string('device_fingerprint', 255)->nullable()->after('ip_address');
                $table->index(['talent_id', 'device_fingerprint']);
            });
        }

        // 3. Add device_fingerprint to followers table if not exists
        if (!Schema::hasColumn('followers', 'device_fingerprint')) {
            Schema::table('followers', function (Blueprint $table) {
                $table->string('device_fingerprint', 255)->nullable()->after('ip_address');
                $table->index(['talent_id', 'device_fingerprint']);
            });
        }

        // 4. Add ip_address and device_fingerprint to comments table if not exists
        if (!Schema::hasColumn('comments', 'ip_address')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->string('ip_address', 45)->nullable()->after('author_name');
                $table->string('device_fingerprint', 255)->nullable()->after('ip_address');
                $table->index(['talent_id', 'ip_address']);
                $table->index(['talent_id', 'device_fingerprint']);
            });
        }

        // 5. Create profile_views table
        if (!Schema::hasTable('profile_views')) {
            Schema::create('profile_views', function (Blueprint $table) {
                $table->id();
                $table->foreignId('talent_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->string('ip_address', 45)->nullable();
                $table->string('device_fingerprint', 255)->nullable();
                $table->timestamps();

                $table->index(['talent_id', 'user_id']);
                $table->index(['talent_id', 'ip_address']);
                $table->index(['talent_id', 'device_fingerprint']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_views');

        if (Schema::hasColumn('comments', 'ip_address')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropColumn(['ip_address', 'device_fingerprint']);
            });
        }

        if (Schema::hasColumn('followers', 'device_fingerprint')) {
            Schema::table('followers', function (Blueprint $table) {
                $table->dropColumn('device_fingerprint');
            });
        }

        if (Schema::hasColumn('likes', 'device_fingerprint')) {
            Schema::table('likes', function (Blueprint $table) {
                $table->dropColumn('device_fingerprint');
            });
        }

        if (Schema::hasColumn('users', 'views_count')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('views_count');
            });
        }
    }
};
