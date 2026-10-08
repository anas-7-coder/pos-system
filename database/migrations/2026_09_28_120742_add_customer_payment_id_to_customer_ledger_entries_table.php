<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_ledger_entries', function (Blueprint $table) {
            $table->foreignId('customer_payment_id')
                ->nullable()
                ->unique()
                ->after('sale_id')
                ->constrained('customer_payments')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('customer_ledger_entries', function (Blueprint $table) {
            $table->dropForeign(['customer_payment_id']);

            $table->dropUnique(['customer_ledger_entries_customer_payment_id_unique']);

            $table->dropColumn('customer_payment_id');
        });
    }
};
