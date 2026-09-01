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
        Schema::table('account_blocks', function (Blueprint $table) {
            $table->integer('attempts_count')->nullable()->default(0)->change();
            $table->string('time_interval')->nullable()->change();
            if (!Schema::hasColumn('account_blocks', 'reason')) {
                $table->text('reason')->nullable()->after('customer_complaint');
            }
            if (!Schema::hasColumn('account_blocks', 'blocked_by')) {
                $table->string('blocked_by')->nullable()->after('reason');
            }
            if (!Schema::hasColumn('account_blocks', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('blocked_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_blocks', function (Blueprint $table) {
            if (Schema::hasColumn('account_blocks', 'reason')) {
                $table->dropColumn('reason');
            }
            if (Schema::hasColumn('account_blocks', 'blocked_by')) {
                $table->dropColumn('blocked_by');
            }
            if (Schema::hasColumn('account_blocks', 'ip_address')) {
                $table->dropColumn('ip_address');
            }
        });
    }
};
