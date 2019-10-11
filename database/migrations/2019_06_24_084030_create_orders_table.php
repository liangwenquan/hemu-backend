<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id', 10000000);
            $table->integer('shop_id')->default(0)->comment('商店id');
            $table->integer('user_id')->comment('用户id')->index();
            $table->decimal('amount', 6, 2)->default(0.00)->comment('订单金额');
            $table->decimal('payed_amount', 6 , 2)->default(0.00)->comment('订单世纪支付金额');
            $table->string('remark')->comment('客户备注');
            $table->tinyInteger('status')->default(0)->comment('0:已取消, 1:已确认待支付，10:已支付');
            $table->dateTime('payed_at')->nullable()->comment('支付时间');
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
}
