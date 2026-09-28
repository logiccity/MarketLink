<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_stock_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('default_quantity')->default(10);
            $table->decimal('default_price', 10, 2);
            $table->boolean('is_available')->default(true);
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['farmer_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_stock_templates');
    }
};
