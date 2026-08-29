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
        Schema::create('contact_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('requester_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('requester_type')->default('guest'); // guest
            $table->string('requester_full_name');
            $table->string('contact_type'); // email, phone, whatsapp
            $table->string('contact_value');
            $table->string('region')->nullable(); // optional location/region of requester
            $table->text('message')->nullable();
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected, Completed
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('response')->nullable();
            $table->text('admin_notes')->nullable(); // admin-only notes
            $table->text('staff_notes')->nullable(); // customer care internal notes
            $table->string('ip_address')->nullable(); // spam protection
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_requests');
    }
};
