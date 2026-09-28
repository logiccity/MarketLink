<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_stock_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->nullable()->constrained('weekly_stock_templates')->nullOnDelete();
            $table->foreignId('farmer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->date('week_date'); // Date of the week / market date
            $table->integer('quantity')->default(0);
            $table->decimal('price', 10, 2);
            $table->string('availability_status')->default('available'); // available, low_stock, sold_out, temporarily_unavailable
            $table->timestamps();

            $table->unique(['farmer_id', 'product_id', 'week_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_stock_items');
    }
};
