<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plug_id')->index()->comment('插件ID');
            $table->string('plug_only_id',60)->index()->comment('插件唯一ID');
            $table->integer('user_id')->index()->comment('用户ID');
            $table->integer('wwb')->comment('窝窝币');
            $table->tinyInteger('status')->default(0)->comment('支付状态 0失败 1成功');
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
