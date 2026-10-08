<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('register_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('register_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();

            $table->timestamp('opened_at');

            $table->timestamp('closed_at')->nullable();

            $table->decimal('opening_cash', 15, 2)->default(0);

            $table->decimal('expected_cash', 15, 2)->nullable();

            $table->decimal('actual_cash', 15, 2)->nullable();

            $table->decimal('difference', 15, 2)->nullable();

            $table->enum('status', ['Open', 'Closed'])->default('Open');

            $table->timestamps();

            $table->index(['register_id', 'status']);

            $table->index(['branch_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('register_sessions');
    }
};
