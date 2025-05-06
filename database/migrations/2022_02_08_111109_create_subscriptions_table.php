<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('customer_car_id');
            $table->unsignedBigInteger('plan_id');
            $table->enum('payment_mode', ['online', 'offline']);
            $table->dateTime("starts_at")->nullable();
            $table->dateTime("ends_at")->nullable();
            $table->dateTime("next_renew_date")->nullable();
            $table->dateTime("last_renewed_date")->nullable();
            $table->integer("total_billing_cycles")->default(12);
            $table->integer("completed_billing_cycles")->default(0);
            $table->json('preferences');
            $table->enum('status', ['created', 'pending', 'expired', 'active', 'cancelled'])->default('created');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id', 'customer_car_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
