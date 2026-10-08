<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_balances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('warehouse_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('product_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('product_variant_id')
                ->nullable()
                ->constrained('product_variants')
                ->restrictOnDelete();

            /*
             * Unique identity for:
             * warehouse + product + variant
             *
             * 0 represents "no variant".
             */
            $table->string('balance_key')->unique();

            $table->decimal('quantity', 15, 4)->default(0);

            $table->decimal('reserved_quantity', 15, 4)->default(0);

            $table->timestamps();

            $table->index(['warehouse_id', 'product_id', 'product_variant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_balances');
    }
};
