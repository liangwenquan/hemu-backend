<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->comment('订单id')->index();
            $table->integer('product_id')->comment('商品id');
            $table->integer('quantity')->comment('商品数量');
            $table->decimal('price', 6 , 2)->default(0.00)->comment('商品金额');
            $table->decimal('payed_price', 6 , 2)->default(0.00)->comment('商品订单金额');
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
        Schema::dropIfExists('order_items');
    }
}
