<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarServiceRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('car_service_records')) {
            Schema::create('car_service_records', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('service_unit_id');
                $table->unsignedBigInteger('customer_id');
                $table->unsignedBigInteger('booking_id');
                $table->text('type')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('service_unit_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('car_service_images')) {
            Schema::create('car_service_images', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('car_service_record_id');
                $table->enum('wash_type', ['before_wash', 'after_wash'])->default('before_wash');
                $table->string('image_type')->nullable(); // optional: e.g., 'interior', 'damage'
                $table->string('image_path')->default('default_car_image.png');
                $table->timestamps();

                $table->foreign('car_service_record_id')->references('id')->on('car_service_records')->onDelete('cascade');
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
        Schema::dropIfExists('car_service_images');
        Schema::dropIfExists('car_service_records');
    }
}
