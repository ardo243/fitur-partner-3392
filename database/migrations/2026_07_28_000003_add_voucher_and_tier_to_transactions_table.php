<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete()->after('event_id');
            $table->foreignId('ticket_tier_id')->nullable()->constrained('ticket_tiers')->nullOnDelete()->after('voucher_id');
            $table->integer('discount_amount')->default(0)->after('total_price'); // potongan diskon yang diterapkan
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['voucher_id']);
            $table->dropForeign(['ticket_tier_id']);
            $table->dropColumn(['voucher_id', 'ticket_tier_id', 'discount_amount']);
        });
    }
};
