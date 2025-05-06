<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('booking_id');
            $table->string('refund_type')->default('full');
            $table->double('amount', 64, 0);
            $table->json('data')->nullable();
            $table->unsignedBigInteger('processed_by');
            $table->dateTime('refund_date')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->string('status')->default('initiated');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refunds');
    }
}
