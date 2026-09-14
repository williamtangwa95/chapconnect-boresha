<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Find all admin users who are currently marked as blocked and unblock them
        $adminIds = DB::table('users')
            ->whereIn('role', ['admin', 'super_admin', 'superadmin'])
            ->pluck('id');

        if ($adminIds->isNotEmpty()) {
            DB::table('users')
                ->whereIn('id', $adminIds)
                ->update(['is_blocked' => false]);

            DB::table('account_blocks')
                ->whereIn('user_id', $adminIds)
                ->where('status', 'blocked')
                ->update([
                    'status' => 'unblocked',
                    'unblocked_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No operation needed for down migration
    }
};
