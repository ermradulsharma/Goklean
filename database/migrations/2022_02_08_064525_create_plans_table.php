<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('razorpay_id')->nullable();
            $table->string('description')->nullable();
            $table->enum('wash_qty_per', ['weekly', 'monthly'])->default('weekly')->index();
            $table->unsignedInteger('wash_qty');
            $table->enum('product_group', ['wash', 'service'])->default('wash')->index();
            $table->unsignedInteger('sort_order')->default(1);
            $table->boolean('is_popular')->default(0);
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('plans');
    }
}
