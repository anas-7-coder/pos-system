<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('name');

            $table->string('sku')->unique();

            $table->decimal('cost_price', 15, 2)->nullable();

            $table->decimal('selling_price', 15, 2)->nullable();

            /*
             * Stores attributes such as:
             *
             * {
             *     "color": "Red",
             *     "size": "Medium"
             * }
             */
            $table->json('attributes')->nullable();

            $table->boolean('is_default')->default(false);

            $table->enum('status', ['Active', 'Inactive'])->default('Active');

            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
