<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    Schema::create('orders', function (Blueprint $table) {
    $table->id();

    $table->string('invoice')->unique();
    $table->string('table_number');

    $table->string('payment_method');
    $table->string('payment_status')->default('unpaid');
    $table->string('order_status')->default('pending');

    $table->integer('total');

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
        Schema::dropIfExists('orders');
    }
};
