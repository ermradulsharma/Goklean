<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->morphs('item');
            $table->enum('discount_type', ['fixed', 'percentage'])->default('percentage');
            $table->unsignedInteger('discount_value');
            $table->enum('discount_method', ['auto', 'coupon'])->default('auto');
            $table->string('coupon_code')->nullable();
            $table->dateTime('valid_from');
            $table->dateTime('valid_until');
            $table->unsignedInteger('maximum_discount_value')->default(0);
            $table->unsignedInteger('minimum_cart_amount')->default(0);
            $table->boolean('is_private')->default(1);
            $table->unsignedBigInteger('user_group_id')->nullable();
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('discounts');
    }
}
