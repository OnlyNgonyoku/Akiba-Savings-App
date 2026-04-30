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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->morphs('walletable');              // walletable_type + walletable_id
            $table->string('type');                    // personal, group, goal_escrow, fundraiser, system_collection
            $table->decimal('balance', 20, 2)->default(0.00);
            $table->timestamps();

            // One wallet per type per owner
            $table->unique(['walletable_type', 'walletable_id', 'type'], 'wallets_owner_type_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
