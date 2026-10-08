<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sale_return_id')
                ->unique()
                ->constrained('sale_returns')
                ->restrictOnDelete();

            $table->foreignId('sale_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('register_session_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('payment_method', 50);

            $table->decimal('amount', 15, 2);

            $table->string('reference', 255)->nullable();

            $table->dateTime('refunded_at');

            $table->string('status', 20)->default('posted');

            $table->timestamps();

            $table->index(['sale_id', 'status']);
            $table->index(['register_session_id', 'refunded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
