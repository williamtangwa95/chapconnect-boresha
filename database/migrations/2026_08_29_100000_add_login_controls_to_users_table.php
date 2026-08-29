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
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->boolean('is_blocked')->default(false)->after('role');
        });

        Schema::create('failed_login_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('attempted_at')->useCurrent();
        });

        Schema::create('account_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('attempts_count');
            $table->string('time_interval');
            $table->text('customer_complaint')->nullable();
            $table->string('requested_by')->nullable();
            $table->string('issued_by')->nullable();
            $table->enum('status', ['blocked', 'unblocked'])->default('blocked');
            $table->timestamp('unblocked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_blocks');
        Schema::dropIfExists('failed_login_attempts');

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->dropColumn('is_blocked');
        });
    }
};
