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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('company'); // e.g. CRDB Bank, NMB Bank, TigoPesa, HaloPesa, M-Pesa, Airtel Money
            $table->string('account_name'); // e.g. CHAPCONNECT LIMITED / LIPA NAMBA
            $table->string('account_number'); // e.g. 0150123456700 or Lipa Code
            $table->string('logo_path')->nullable(); // Uploaded logo or preset icon
            $table->text('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default initial payment channels if table is empty
        DB::table('payment_methods')->insert([
            [
                'company' => 'CRDB Bank',
                'account_name' => 'CHAPCONNECT LIMITED',
                'account_number' => '0152847592000',
                'logo_path' => '/images/payment_methods/crdb.png',
                'instructions' => 'Weka fedha au hamisha kwa kutumia CRDB SimBanking au TISP kwenda Namba ya Akaunti hapo juu.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company' => 'NMB Bank',
                'account_name' => 'CHAPCONNECT LIMITED',
                'account_number' => '20110048291',
                'logo_path' => '/images/payment_methods/nmb.png',
                'instructions' => 'Hamisha fedha kupitia NMB Mkononi au TISP kwenda Akaunti hapo juu.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company' => 'TigoPesa',
                'account_name' => 'CHAPCONNECT LIPA',
                'account_number' => '554433',
                'logo_path' => '/images/payment_methods/tigopesa.png',
                'instructions' => 'Piga *150*01# -> Lipa kwa TigoPesa -> Weka Lipa Namba 554433.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company' => 'HaloPesa',
                'account_name' => 'CHAPCONNECT LIPA',
                'account_number' => '667788',
                'logo_path' => '/images/payment_methods/halopesa.png',
                'instructions' => 'Piga *150*88# -> Lipa Biashara -> Weka Namba 667788.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
