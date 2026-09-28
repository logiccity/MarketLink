<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug', 190)->unique();
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('city')->nullable();
            $table->json('operating_days')->nullable(); // e.g. ["Saturday", "Sunday"]
            $table->string('opening_time')->default('08:00');
            $table->string('closing_time')->default('14:00');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('map_url')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};
