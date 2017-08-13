<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRechargeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index()->comment('用户ID');
            $table->tinyInteger('recharge_type')->comment('充值类型');
            $table->float('recharge_amount', 11,2)->comment('充值金额 2位小数');
            $table->integer('recharge_wwb')->comment('充值获得的窝窝币');
            $table->integer('giving_wwb')->comment('充值赠送的窝窝币');
            $table->tinyInteger('status')->default(0)->comment('状态 0初始状态 1用户未支付 9支付成功');
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
        Schema::dropIfExists('recharges');
    }
}
