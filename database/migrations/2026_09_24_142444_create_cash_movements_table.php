<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_movements', function (Blueprint $table) {

            $table->id();

            $table->foreignId('register_session_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('payment_id')
                ->nullable()
                ->constrained('payments')
                ->restrictOnDelete();

            $table->string('type', 30);

            $table->decimal('amount', 15, 2);

            $table->string('reference', 255)->nullable();

            $table->text('notes')->nullable();

            $table->timestamp('occurred_at');

            $table->timestamps();

            $table->index(['register_session_id', 'type', 'occurred_at']);

            $table->unique(['payment_id', 'type'], 'cash_movements_payment_type_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_movements');
    }
};
