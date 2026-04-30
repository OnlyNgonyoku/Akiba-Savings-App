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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->morphs('goalable');                // belongs to User or Group
            $table->string('name');
            $table->decimal('target_amount', 20, 2);
            $table->dateTime('deadline')->nullable();
            $table->string('status')->default('active'); // active, completed, cancelled
            $table->foreignId('wallet_id')->nullable()->constrained('wallets')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
