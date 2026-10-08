<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_groups', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();

            $table->string('code')->unique();

            $table->text('description')->nullable();

            $table->string('status')->default('Active');

            $table->timestamps();

            $table->softDeletes();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_groups');
    }
};
