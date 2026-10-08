<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sale_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('warehouse_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('completed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('return_number')->unique();

            $table->dateTime('return_date');

            $table->string('status', 20)->default('draft');

            $table->decimal('subtotal', 15, 2)->default(0);

            $table->decimal('discount_amount', 15, 2)->default(0);

            $table->decimal('tax_amount', 15, 2)->default(0);

            $table->decimal('grand_total', 15, 2)->default(0);

            $table->text('notes')->nullable();

            $table->dateTime('completed_at')->nullable();

            $table->timestamps();

            $table->index(['sale_id', 'status']);
            $table->index(['branch_id', 'return_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_returns');
    }
};
