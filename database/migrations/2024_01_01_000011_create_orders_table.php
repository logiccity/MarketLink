<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('farmer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('market_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pickup_slot_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_number', 190)->unique(); // e.g. ML-2026-000001
            $table->date('pickup_date');
            $table->string('status')->default('PLACED'); // PLACED, ACCEPTED, DECLINED, READY_FOR_PICKUP, COMPLETED, CANCELLED
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('payment_method')->default('Pay at Market Pickup');
            $table->text('notes')->nullable();
            $table->text('decline_reason')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->string('cancelled_by')->nullable(); // customer, farmer, admin
            $table->timestamp('placed_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
