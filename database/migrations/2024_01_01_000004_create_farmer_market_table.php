<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmer_market', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('market_id')->constrained()->cascadeOnDelete();
            $table->string('stall_identifier')->nullable();
            $table->timestamps();

            $table->unique(['farmer_id', 'market_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmer_market');
    }
};
