<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('stall_name');
            $table->string('contact_person');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('banner_image')->nullable();
            $table->json('operating_days')->nullable(); // e.g. ["Wednesday", "Saturday", "Sunday"]
            $table->string('pickup_windows')->nullable(); // e.g. "8:00 AM - 1:00 PM"
            $table->integer('order_cutoff_hours')->default(12); // Cutoff X hours before pickup window
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('approval_status')->default('pending'); // pending, approved, rejected, suspended
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};
