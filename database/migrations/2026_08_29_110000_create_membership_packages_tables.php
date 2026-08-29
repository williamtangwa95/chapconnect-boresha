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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('phone_visibility')->default('No'); // Yes or No
            $table->integer('max_images')->default(5);
            $table->integer('max_videos')->default(2);
            $table->integer('max_news')->default(3);
            $table->decimal('price', 12, 2)->default(0.00);
            $table->integer('duration')->default(30);
            $table->string('duration_unit')->default('days'); // days, months, years
            $table->string('package_type')->default('Free'); // Free or To Pay
            $table->string('status')->default('Active'); // Active or Inactive
            $table->timestamps();
        });

        Schema::create('user_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->nullable()->constrained()->onDelete('set null');
            $table->string('package_name_snapshot');
            $table->decimal('price_snapshot', 12, 2);
            $table->integer('duration_snapshot');
            $table->string('duration_unit_snapshot');
            $table->string('phone_visibility_snapshot');
            $table->integer('max_images_snapshot');
            $table->integer('max_videos_snapshot');
            $table->integer('max_news_snapshot');
            $table->string('package_type_snapshot');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('active'); // active, expired, cancelled
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_package_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->nullable()->constrained()->onDelete('set null');
            $table->string('package_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration');
            $table->string('duration_unit');
            $table->decimal('amount', 12, 2);
            $table->decimal('amount_paid', 12, 2)->default(0.00);
            $table->string('payment_status')->default('Unpaid'); // Unpaid, Paid, Cancelled, Overdue
            $table->date('invoice_date');
            $table->date('due_date');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Seed default packages
        $standardPackageId = DB::table('packages')->insertGetId([
            'name' => 'Standard',
            'description' => 'Default free package with basic limitations.',
            'phone_visibility' => 'No',
            'max_images' => 5,
            'max_videos' => 2,
            'max_news' => 3,
            'price' => 0.00,
            'duration' => 365,
            'duration_unit' => 'days',
            'package_type' => 'Free',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('packages')->insert([
            [
                'name' => 'Premium',
                'description' => 'Premium package for professional talents.',
                'phone_visibility' => 'Yes',
                'max_images' => 20,
                'max_videos' => 10,
                'max_news' => 15,
                'price' => 20000.00,
                'duration' => 30,
                'duration_unit' => 'days',
                'package_type' => 'To Pay',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'VIP',
                'description' => 'Exclusive VIP package with high limits and visibility.',
                'phone_visibility' => 'Yes',
                'max_images' => 50,
                'max_videos' => 25,
                'max_news' => 30,
                'price' => 50000.00,
                'duration' => 30,
                'duration_unit' => 'days',
                'package_type' => 'To Pay',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Assign standard package to all existing users with role = 'user'
        $users = DB::table('users')->where('role', 'user')->get();
        foreach ($users as $user) {
            DB::table('user_packages')->insert([
                'user_id' => $user->id,
                'package_id' => $standardPackageId,
                'package_name_snapshot' => 'Standard',
                'price_snapshot' => 0.00,
                'duration_snapshot' => 365,
                'duration_unit_snapshot' => 'days',
                'phone_visibility_snapshot' => 'No',
                'max_images_snapshot' => 5,
                'max_videos_snapshot' => 2,
                'max_news_snapshot' => 3,
                'package_type_snapshot' => 'Free',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(365)->toDateString(),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('user_packages');
        Schema::dropIfExists('packages');
    }
};
