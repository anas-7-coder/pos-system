<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_counts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('warehouse_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('count_number')->unique();

            $table->date('count_date');

            $table->string('status')->default('draft');

            $table->text('notes')->nullable();

            $table->timestamp('posted_at')->nullable();

            $table->foreignId('posted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['warehouse_id', 'status']);

            $table->index('count_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_counts');
    }
};
