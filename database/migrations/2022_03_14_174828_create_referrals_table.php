<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('referred_customer_id');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->integer('amount');
            $table->string('status')->default('pending'); // pending, accepted, rejected
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referrals');
    }
}
