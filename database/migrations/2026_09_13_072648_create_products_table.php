<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('unit_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('tax_class_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('name');
            $table->string('sku')->unique();

            $table->text('description')->nullable();

            $table->enum('product_type', ['stockable', 'service'])->default('stockable');

            $table->decimal('cost_price', 15, 2)->default(0);

            $table->decimal('selling_price', 15, 2)->default(0);

            $table->boolean('track_inventory')->default(true);

            $table->boolean('allow_negative_stock')->default(false);

            $table->enum('status', ['Active', 'Inactive'])->default('Active');

            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->index('brand_id');
            $table->index('unit_id');
            $table->index('tax_class_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
