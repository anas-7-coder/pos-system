<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('register_session_id')
                ->nullable()
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('payment_number')->unique();

            $table->string('payment_method', 50);

            $table->decimal('amount', 15, 2);

            $table->decimal('received_amount', 15, 2)->default(0);

            $table->decimal('change_amount', 15, 2)->default(0);

            $table->string('reference', 255)->nullable();

            $table->dateTime('paid_at');

            $table->string('status', 20)->default('posted');

            $table->timestamps();

            $table->index(['customer_id', 'paid_at']);

            $table->index(['branch_id', 'paid_at']);

            $table->index(['register_session_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_payments');
    }
};
