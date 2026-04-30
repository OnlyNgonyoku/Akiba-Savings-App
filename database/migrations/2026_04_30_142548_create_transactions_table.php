<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('initiator');              // user or system
            $table->string('type');                   // deposit, transfer, contribution, payout, withdrawal, fee
            $table->decimal('amount', 20, 2);
            $table->string('reference')->unique();    // human‑readable, e.g. TXN-2026...
            $table->string('idempotency_key')->nullable()->unique();
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
