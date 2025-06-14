<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('service_complaints')) {
            Schema::create('service_complaints', function (Blueprint $table) {
                $table->id();
                $table->string('complaint_no')->unique();
                $table->unsignedBigInteger('customer_id');
                $table->unsignedBigInteger('booking_id');
                $table->unsignedBigInteger('service_unit_id');
                $table->string('subject');
                $table->string('description')->nullable();
                $table->longText('su_reply')->nullable();
                $table->enum('status', ['created', 'pending', 'resolved', 'closed', 'completed'])->default('created');
                $table->timestamps();

                $table->foreign('service_unit_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            });
        }
        if (!Schema::hasTable('service_complaints_images')) {
            Schema::create('service_complaints_images', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('service_complaints_id');
                $table->string('image_path')->default('default_car_image.png');
                $table->timestamps();

                $table->foreign('service_complaints_id')->references('id')->on('service_complaints')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_complaints');
    }
}
