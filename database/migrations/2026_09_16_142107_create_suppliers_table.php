<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->string('code')->unique();

            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->text('address')->nullable();

            $table->string('tax_number')->nullable();

            $table->decimal('opening_balance', 15, 2)->default(0);

            $table->decimal('credit_limit', 15, 2)->default(0);

            $table->string('status')->default('Active');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('phone');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
