<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('product_variant_id')
                ->nullable()
                ->constrained('product_variants')
                ->restrictOnDelete();

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained()
                ->restrictOnDelete();

            $table->string('price_type'); // retail, wholesale, special

            $table->decimal('price', 15, 2);

            $table->date('effective_from');
            $table->date('effective_to')->nullable();

            $table->string('status')->default('Active');

            $table->string('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'product_id',
                'product_variant_id',
                'branch_id',
                'price_type',
            ], 'product_price_idx');

            $table->index([
                'effective_from',
                'effective_to',
            ], 'product_price_date_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
