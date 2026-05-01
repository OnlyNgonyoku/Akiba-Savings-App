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
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('wallet_id')->constrained();
            $table->string('entry_type');             // 'debit' or 'credit'
            $table->decimal('amount', 20, 2);
            $table->string('description')->nullable();
            $table->timestamp('created_at')->useCurrent();

            // No updated_at – entries are immutable
            $table->index(['wallet_id', 'created_at']);
            $table->index('transaction_id');

            $table->softDeletes(); // For potential future use, e.g., correcting errors without losing history
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
