<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('customer_car_id');
            $table->unsignedBigInteger('service_unit_id');
            $table->dateTime('date');
            $table->json('data')->nullable();
            $table->string('status')->default('created');
            $table->string('reason')->nullable();
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
        Schema::dropIfExists('schedules');
    }
}
