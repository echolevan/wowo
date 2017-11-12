<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->increments('id');
            $table->string('alipay')->comment('支付宝账户');
            $table->string('alipay_name')->comment('支付宝姓名');
            $table->integer('user_id')->index()->comment('用户ID');
            $table->integer('gold')->comment('提现的金币');
            $table->integer('money')->comment('提现的钱');
            $table->tinyInteger('status')->default(0)->comment('0初始状态 1转账失败 2转账成功');
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
        Schema::dropIfExists('withdraws');
    }
}
