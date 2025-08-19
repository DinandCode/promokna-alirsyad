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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->nullable()->references('id')->on('participants')->onDelete('set null');
            $table->float('amount');
            $table->float('rate');
            $table->float('total_amount');
            $table->string('midtrans_order_id');
            $table->string('midtrans_snap_token');
            $table->enum('status', ['pending', 'paid', 'expired'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
