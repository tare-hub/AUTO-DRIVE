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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('vehicle_categories')->cascadeOnDelete();
            $table->string('brand')->index();
            $table->string('model')->index();
            $table->unsignedSmallInteger('year')->index();
            $table->string('vin')->unique();
            $table->string('license_plate')->unique();
            $table->string('color')->nullable();
            $table->string('transmission')->default('Automatic'); // Automatic, Manual, Dual-Clutch
            $table->string('fuel_type')->default('Petrol'); // Petrol, Diesel, Electric, Hybrid
            $table->unsignedTinyInteger('seating_capacity')->default(5);
            $table->unsignedInteger('mileage')->default(0);
            $table->string('engine_capacity')->nullable(); // e.g. 3.0L Twin-Turbo V6, Electric 400kW
            $table->decimal('daily_rate', 10, 2)->default(0);
            $table->decimal('selling_price', 12, 2)->nullable();
            $table->enum('listing_type', ['rental', 'sale', 'both'])->default('rental')->index();
            $table->enum('status', ['available', 'rented', 'reserved', 'sold', 'maintenance'])->default('available')->index();
            $table->enum('condition', ['new', 'used', 'certified_preowned'])->default('new')->index();
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->string('main_image')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
