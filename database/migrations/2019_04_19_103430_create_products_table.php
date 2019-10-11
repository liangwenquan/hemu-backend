<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('category_id')->nullable()->comment('商品分类id');
            $table->string('name', 60)->default('')->comment('商品名称');
            $table->integer('sold')->default(0)->comment('已售');
            $table->decimal('price', 8 , 2)->default('0.00')->comment('商品价格');
            $table->integer('shop_id')->nullable()->comment('商店id');
            $table->text('content')->comment('product 富文本信息');
            $table->string('cover', 500)->comment('商品滑动图');
            $table->tinyInteger('is_recommend')->default(0)->comment('0:false, 1:true');
            $table->integer('weight')->default(0)->comment('排序权重');
            $table->tinyInteger('enabled')->default(1)->comment('是否展示该条数据');
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
        Schema::dropIfExists('products');
    }
}
