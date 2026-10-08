<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('warehouse_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('register_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('register_session_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('cashier_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('invoice_number')->unique();

            $table->dateTime('sale_date');

            $table->string('status')->default('draft');

            $table->decimal('subtotal', 15, 2)->default(0);

            $table->decimal('discount_amount', 15, 2)->default(0);

            $table->decimal('tax_amount', 15, 2)->default(0);

            $table->decimal('grand_total', 15, 2)->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['branch_id', 'status']);

            $table->index(['register_session_id', 'status']);

            $table->index(['customer_id', 'sale_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
