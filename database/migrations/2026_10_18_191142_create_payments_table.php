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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->string('payable_type');
            $table->unsignedBigInteger('payable_id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', ['credit_card', 'bank_transfer', 'cash', 'simulated_card'])->default('simulated_card');
            $table->enum('payment_status', ['pending', 'partially_paid', 'paid', 'failed', 'refunded'])->default('pending')->index();
            $table->string('transaction_reference')->unique();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['payable_type', 'payable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
