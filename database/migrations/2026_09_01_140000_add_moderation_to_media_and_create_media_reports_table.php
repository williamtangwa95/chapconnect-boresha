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
        Schema::table('media', function (Blueprint $table) {
            $table->string('moderation_status')->default('approved')->after('file_path'); // 'approved', 'flagged', 'pending_review', 'rejected'
            $table->string('moderation_reason')->nullable()->after('moderation_status');
            $table->decimal('moderation_score', 5, 2)->nullable()->after('moderation_reason');
            $table->boolean('is_visible')->default(true)->after('moderation_score');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('is_visible');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->unsignedInteger('report_count')->default(0)->after('reviewed_at');
        });

        Schema::create('media_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained('media')->onDelete('cascade');
            $table->foreignId('reporter_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('reporter_ip', 45)->nullable();
            $table->string('reason'); // 'nudity_nsfw', 'violence', 'copyright', 'spam', 'harassment', 'other'
            $table->text('details')->nullable();
            $table->string('status')->default('pending'); // 'pending', 'actioned', 'dismissed'
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_reports');

        Schema::table('media', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn([
                'moderation_status',
                'moderation_reason',
                'moderation_score',
                'is_visible',
                'reviewed_by',
                'reviewed_at',
                'report_count'
            ]);
        });
    }
};
