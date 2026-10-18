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
        Schema::create('rental_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->date('start_date')->index();
            $table->date('end_date')->index();
            $table->unsignedInteger('total_days')->default(1);
            $table->decimal('daily_rate', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('insurance_fee', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('late_fee', 10, 2)->default(0);
            $table->decimal('damage_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'active', 'completed', 'cancelled'])->default('pending')->index();
            $table->string('pickup_location')->default('AutoDrive Main Flagship Terminal');
            $table->string('return_location')->default('AutoDrive Main Flagship Terminal');
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            // Compound index for checking overlapping booking ranges
            $table->index(['vehicle_id', 'status', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_bookings');
    }
};
