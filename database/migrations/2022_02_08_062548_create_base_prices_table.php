<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_prices', function (Blueprint $table) {
            $table->unsignedBigInteger('car_type_id')->index();
            $table->unsignedInteger('wash_qty')->index();
            $table->double('price', 10, 2);

            $table->foreign('car_type_id')->references('id')->on('car_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('base_prices');
    }
}
