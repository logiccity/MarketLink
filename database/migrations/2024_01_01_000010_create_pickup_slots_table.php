<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pickup_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('market_id')->constrained()->cascadeOnDelete();
            $table->date('pickup_date');
            $table->string('start_time'); // e.g. "09:00"
            $table->string('end_time');   // e.g. "10:30"
            $table->integer('capacity')->default(15);
            $table->integer('booked_count')->default(0);
            $table->dateTime('cutoff_time')->nullable(); // Orders can't be placed or modified after cutoff
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_slots');
    }
};
