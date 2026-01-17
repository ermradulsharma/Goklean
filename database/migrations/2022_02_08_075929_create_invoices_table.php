<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('customer_car_id');
            $table->enum('booking_type', ['single', 'bulk']);
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->unsignedInteger('billing_cycle')->nullable();
            $table->dateTime('billing_cycle_starts')->nullable();
            $table->dateTime('billing_cycle_ends')->nullable();
            $table->double('total_price', 10, 2);
            $table->enum('payment_mode', ['online', 'offline']);
            $table->dateTime('due_date')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('refund_id')->nullable();
            $table->string('reference_id')->nullable();
            $table->json('data')->nullable();
            $table->json('preferences')->nullable();
            $table->boolean('booking_completed')->default(0);
            $table->enum('status', ['created', 'pending', 'failed', 'paid', 'cancelled'])->default('created');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['invoice_id', 'customer_id', 'customer_car_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
