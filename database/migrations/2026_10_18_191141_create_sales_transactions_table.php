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
        Schema::create('sales_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->foreignId('purchase_request_id')->nullable()->constrained('purchase_requests')->nullOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('sales_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('sale_price', 12, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('fees_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->enum('payment_status', ['pending', 'partially_paid', 'paid'])->default('pending')->index();
            $table->date('sale_date');
            $table->string('invoice_number')->nullable()->unique();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_transactions');
    }
};
