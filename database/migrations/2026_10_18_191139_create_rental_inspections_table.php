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
        Schema::create('rental_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_booking_id')->constrained('rental_bookings')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('inspector_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['pickup', 'return'])->index();
            $table->unsignedInteger('odometer_reading');
            $table->string('fuel_level')->default('Full'); // Full, 75%, 50%, 25%, Empty
            $table->string('exterior_condition')->default('Pristine');
            $table->string('interior_condition')->default('Clean');
            $table->text('damages_notes')->nullable();
            $table->decimal('damage_charge', 10, 2)->default(0);
            $table->unsignedInteger('late_days')->default(0);
            $table->decimal('late_fee', 10, 2)->default(0);
            $table->timestamp('inspection_date')->useCurrent();
            $table->json('photos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_inspections');
    }
};
