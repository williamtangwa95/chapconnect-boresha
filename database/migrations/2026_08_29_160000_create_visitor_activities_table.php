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
        if (!Schema::hasTable('visitor_activities')) {
            Schema::create('visitor_activities', function (Blueprint $table) {
                $table->id();
                $table->string('ip_address', 45)->nullable();
                $table->string('location', 255)->nullable();
                $table->string('url', 2048)->nullable();
                $table->string('method', 10)->nullable();
                $table->string('user_agent', 1024)->nullable();
                $table->string('device_type', 50)->nullable(); // Desktop, Mobile, Tablet
                $table->string('browser', 50)->nullable(); // Chrome, Safari, Firefox, Edge
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->string('session_id', 255)->nullable();
                $table->timestamps();

                $table->index('created_at');
                $table->index('ip_address');
                $table->index('session_id');
                $table->index('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_activities');
    }
};
